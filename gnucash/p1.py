#!/usr/bin/env python3

import sys
import json

from gnucash import GncNumeric, Session, SessionOpenMode

def find_account_by_name(root_account, name):
    # Check the current account
    if root_account.name == name:
        return root_account

    # Recursively search through children
    for child in root_account.get_children():
        found = find_account_by_name(child, name)
        if found:
            return found
    return None


if __name__ == '__main__':
    print('Name,Commodity,Totals,Totals (USD)')
    with Session(sys.argv[1], SessionOpenMode.SESSION_READ_ONLY) as session:
        commodity_table = session.book.get_table()
        USD = commodity_table.lookup("ISO4217", "USD")
        root_account = session.book.get_root_account()
        found = find_account_by_name(root_account, 'secret25')
        ignore = 1

