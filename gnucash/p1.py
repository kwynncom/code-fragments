#!/usr/bin/env python3

import sys

from gnucash import GncNumeric, Session, SessionOpenMode

def find_account_by_name(root_account, name):
    if root_account.name == name:
        return root_account
    for child in root_account.get_children():
        found = find_account_by_name(child, name)
        if found:
            return found
    return None

if __name__ == '__main__':
    with Session(sys.argv[1], SessionOpenMode.SESSION_READ_ONLY) as session:
        root_account = session.book.get_root_account()

        relevant_aname = 'secret25'

        account = find_account_by_name(root_account, relevant_aname)
        splits = account.GetSplitList()

        for split in splits:
            transaction = split.GetParent()
            trans_splits = transaction.GetSplitList()
            for trans_split in trans_splits:
                aname = trans_split.GetAccount().GetName()
                if aname == relevant_aname:
                    continue

                print(trans_split.GetValue().to_double(), aname, transaction.GetDescription())
        ignore = 1

