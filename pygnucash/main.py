import json
import sys
from datetime import date, datetime
from pathlib import Path

from gnucash import Session, SessionOpenMode

CONFIG_PATH = Path(__file__).resolve().parent / "config.json"


def load_gnucash_file():
    with CONFIG_PATH.open() as config_file:
        config = json.load(config_file)
    try:
        return config["gnucash_file"]
    except KeyError:
        sys.exit(f"{CONFIG_PATH} is missing gnucash_file")


def as_datetime(value):
    if isinstance(value, datetime):
        return value
    if isinstance(value, date):
        return datetime(value.year, value.month, value.day)
    return datetime.fromtimestamp(int(value))


def each_transaction(root):
    seen = set()
    accounts = [root, *root.get_descendants()]
    for account in accounts:
        for split in account.GetSplitList():
            transaction = split.GetParent()
            guid = transaction.GetGUID().to_string()
            if guid in seen:
                continue
            seen.add(guid)
            yield transaction


def currency_code(transaction):
    currency = transaction.GetCurrency()
    if currency is None:
        return ""
    return currency.get_mnemonic() or ""


def format_transaction(transaction):
    entered = as_datetime(transaction.GetDateEntered())
    posted = as_datetime(transaction.GetDate())
    number = (transaction.GetNum() or "").strip()
    description = transaction.GetDescription() or ""
    code = currency_code(transaction)
    lines = [
        f"Entered:     {entered.strftime('%m/%d/%Y %H:%M:%S')}",
        f"Posted:      {posted.strftime('%m/%d/%Y')}",
    ]
    if number:
        lines.append(f"Num:         {number}")
    lines.append(f"Description: {description}")
    if transaction.GetVoidStatus():
        lines.append("Void:        yes")
    for split in transaction.GetSplitList():
        amount = f"{split.GetValue().to_double():,.2f}"
        if code:
            amount = f"{amount} {code}"
        account = split.GetAccount().get_full_name()
        lines.append(f"  {amount:>16}  {account}")
    return "\n".join(lines)


def show_last_transaction():
    path = load_gnucash_file()
    session = Session(path, SessionOpenMode.SESSION_READ_ONLY)
    try:
        transactions = list(each_transaction(session.book.get_root_account()))
        if not transactions:
            print("No transactions in the book.")
            return
        latest = max(transactions, key=lambda tx: tx.GetDateEntered())
        latest_entered = latest.GetDateEntered()
        chosen = [tx for tx in transactions if tx.GetDateEntered() == latest_entered]
        chosen.sort(key=lambda tx: (tx.GetDate(), tx.GetGUID().to_string()))
        blocks = [format_transaction(tx) for tx in chosen]
        searched = len(transactions)
    finally:
        session.end()

    print(f"Searched {searched} transactions.")
    if len(blocks) > 1:
        print(f"{len(blocks)} transactions share the latest entered time.")
    print()
    print("\n\n".join(blocks))


def main(argv):
    if argv == ["last", "xact"]:
        show_last_transaction()
        return
    sys.exit("usage: python3 main.py last xact")


if __name__ == "__main__":
    main(sys.argv[1:])
