import json
import sys
from datetime import date, datetime
from pathlib import Path

from gnucash import Session, SessionOpenMode
from gnucash.gnucash_core_c import (
    ACCT_TYPE_ASSET,
    ACCT_TYPE_BANK,
    ACCT_TYPE_CASH,
    ACCT_TYPE_CHECKING,
    ACCT_TYPE_CREDIT,
    ACCT_TYPE_CREDITLINE,
    ACCT_TYPE_CURRENCY,
    ACCT_TYPE_LIABILITY,
    ACCT_TYPE_MONEYMRKT,
    ACCT_TYPE_MUTUAL,
    ACCT_TYPE_PAYABLE,
    ACCT_TYPE_RECEIVABLE,
    ACCT_TYPE_SAVINGS,
    ACCT_TYPE_STOCK,
)

NET_WORTH_TYPES = {
    ACCT_TYPE_ASSET,
    ACCT_TYPE_BANK,
    ACCT_TYPE_CASH,
    ACCT_TYPE_CHECKING,
    ACCT_TYPE_CREDIT,
    ACCT_TYPE_CREDITLINE,
    ACCT_TYPE_CURRENCY,
    ACCT_TYPE_LIABILITY,
    ACCT_TYPE_MONEYMRKT,
    ACCT_TYPE_MUTUAL,
    ACCT_TYPE_PAYABLE,
    ACCT_TYPE_RECEIVABLE,
    ACCT_TYPE_SAVINGS,
    ACCT_TYPE_STOCK,
}

CONFIG_PATH = Path(__file__).resolve().parent / "config.json"
FALLBACK_CONFIG = Path(__file__).resolve().parent / "config.test.json"


def load_gnucash_file(config_path):
    with Path(config_path).open() as config_file:
        config = json.load(config_file)
    path = config.get("gnucash_file") or ""
    if not str(path).strip():
        sys.exit(f"{config_path} has no gnucash_file")
    return path


def can_open(path):
    try:
        with open(path, "rb"):
            return True
    except OSError:
        return False


def book_path():
    live = load_gnucash_file(CONFIG_PATH)
    if can_open(live):
        return live
    fallback = load_gnucash_file(FALLBACK_CONFIG)
    if can_open(fallback):
        return fallback
    sys.exit(f"cannot open {live} or {fallback}")


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


def money(amount, code):
    text = f"{amount:,.2f}"
    if code:
        return f"{text} {code}"
    return text


def book_currency(root):
    commodity = root.GetCommodity()
    if commodity is not None and commodity.is_currency():
        return commodity
    counts = {}
    for account in root.get_descendants():
        item = account.GetCommodity()
        if item is None or not item.is_currency():
            continue
        code = item.get_mnemonic()
        count, chosen = counts.get(code, (0, item))
        counts[code] = (count + 1, chosen)
    if not counts:
        return None
    return max(counts.values(), key=lambda pair: pair[0])[1]


def show_net_worth():
    path = book_path()
    print(f"Book: {path}")
    session = Session(path, SessionOpenMode.SESSION_READ_ONLY)
    try:
        root = session.book.get_root_account()
        currency = book_currency(root)
        if currency is None:
            lines, total, code = None, None, None
        else:
            code = currency.get_mnemonic() or ""
            lines = []
            total = 0.0
            for account in root.get_children():
                subtotal = 0.0
                for item in (account, *account.get_descendants()):
                    if item.GetType() not in NET_WORTH_TYPES:
                        continue
                    subtotal += item.GetBalanceInCurrency(currency, False).to_double()
                subtotal = round(subtotal, 2)
                if subtotal == 0:
                    continue
                total += subtotal
                lines.append(f"{money(subtotal, code):>20}  {account.GetName()}")
            total = round(total, 2)
    finally:
        session.end()

    if lines is None:
        sys.exit("The book has no currency.")

    for line in lines:
        print(line)
    print(f"{money(total, code):>20}  Net worth")


def show_last_transaction():
    path = book_path()
    print(f"Book: {path}")
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
    if argv == ["net", "worth"]:
        show_net_worth()
        return
    sys.exit("usage: python3 main.py last xact | net worth")


if __name__ == "__main__":
    main(sys.argv[1:])
