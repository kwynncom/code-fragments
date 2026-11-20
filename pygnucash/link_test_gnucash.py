"""Hard-link the live GnuCash book into /var/kwynn/gnucash for grok to read.

The link shares the live file's inode. GnuCash saves by replacing the file,
which leaves this link on the previous inode. That is the test snapshot.
Group and mode changes apply to the live book until that replacement.
"""

import grp
import json
import os
import sys
from datetime import datetime
from pathlib import Path

ROOT = Path(__file__).resolve().parent
LIVE_CONFIG = ROOT / "config.json"
TEST_CONFIG = ROOT / "config.test.json"
LINK_DIR = Path("/var/kwynn/gnucash")
GROK_GROUP = "grok"


def load_json(path):
    with path.open() as handle:
        return json.load(handle)


def snapshot_path(prefix, source):
    stamped = datetime.fromtimestamp(source.stat().st_mtime).strftime("%Y-%m-%d")
    return LINK_DIR / f"{prefix}{stamped}.gnucash"


def link_snapshot(source, dest):
    if dest.is_symlink():
        sys.exit(f"{dest} is a symlink. Remove it, then run this again.")
    if dest.exists():
        try:
            same = dest.samefile(source)
        except OSError:
            same = False
        if not same:
            sys.exit(
                f"{dest} already exists and is not the current book. "
                "Remove that file, then run this again."
            )
        return
    try:
        os.link(source, dest)
    except OSError as err:
        sys.exit(f"could not hard-link {source} to {dest}: {err}")


def grant_grok_read(dest):
    gid = grp.getgrnam(GROK_GROUP).gr_gid
    try:
        os.chown(dest, -1, gid)
        os.chmod(dest, 0o640)
    except PermissionError as err:
        sys.exit(f"could not set group {GROK_GROUP} and mode 640 on {dest}: {err}")


def write_test_config(test, dest):
    test["gnucash_file"] = os.fspath(dest)
    TEST_CONFIG.write_text(json.dumps(test, indent=2) + "\n")


def main():
    live = load_json(LIVE_CONFIG)
    source_setting = live.get("gnucash_file") or ""
    if not str(source_setting).strip():
        sys.exit(f"{LIVE_CONFIG} is missing gnucash_file")

    test = load_json(TEST_CONFIG)
    prefix = test.get("prefix") or ""
    if not str(prefix).strip():
        sys.exit(f"{TEST_CONFIG} is missing prefix")

    source = Path(source_setting).expanduser().resolve(strict=True)
    if not source.is_file():
        sys.exit(f"not a file: {source}")

    dest = snapshot_path(prefix, source)
    link_snapshot(source, dest)
    grant_grok_read(dest)
    write_test_config(test, dest)

    print(f"Hard link: {dest}")
    print(f"Group {GROK_GROUP}, mode 640.")
    print("That group and mode are on the live book until GnuCash replaces the file.")
    print(f"Test config: {TEST_CONFIG}")


if __name__ == "__main__":
    main()
