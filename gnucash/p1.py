#!/usr/bin/env python3

import sys
from datetime import datetime
import json

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

        tot = 0
        list = []
        i = 0

        for split in splits:
            transaction = split.GetParent()
            trans_splits = transaction.GetSplitList()
            for trans_split in trans_splits:
                aname = trans_split.GetAccount().GetName()
                if aname == relevant_aname:
                    continue

                trans_date = transaction.GetDate()

                # Convert to human-readable format
                hu = trans_date.strftime('%m/%d')
                huyr = trans_date.strftime('%m/%d/%Y')
                f = trans_split.GetValue().to_double()
                fs =  "{:,.2f}".format(f)
                tot += f
                tots = "{:,.2f}".format(tot)
                descr = transaction.GetDescription()
                recon = trans_split.GetReconcile()

                o = {
                    'hu' : hu,
                    'd'  : descr,
                    'nm' : aname,
                    'r'  : recon,
                    'f'  : f,
                    'bal' : round(tot, 2),
                    'huyr' : huyr,
                    'U' : trans_date.timestamp(),
                    'i' : i
                }

                i += 1

                list.append(o)

                # print(hu, descr , aname, recon, fs, tots)

        json.dump(list, sys.stdout, indent=4)
        print()

        ignore = 1

