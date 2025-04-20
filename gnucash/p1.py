import sys
from datetime import datetime
import json

from gnucash import GncNumeric, Session, SessionOpenMode

def find_account_by_name(root_account, name):
    if root_account.name == name:
        return root_account
    for child in root_account.get_children():
        ckname = child.name
        found = find_account_by_name(child, name)
        if found:
            return found
    return None

if __name__ == '__main__':
    with Session(sys.argv[1], SessionOpenMode.SESSION_READ_ONLY) as session:

# Remember that GNUCash creates a new XML file with each save, so hard links don't work

        relevant_aname = 'secret25'

        root_account = session.book.get_root_account()
        account = find_account_by_name(root_account, relevant_aname)
        splits = account.GetSplitList()

        iacct = 0
        tot = 0
        list = []

        asj = 1 # as JSON

        for split in splits:

            transaction = split.GetParent()
            trans_splits = transaction.GetSplitList()
            for i, trans_split in enumerate(trans_splits):
                recon = '?'
                aname = trans_split.GetAccount().GetName()

                if aname == relevant_aname:
                    continue
                else :
                    nlen = len(trans_splits)
                    ir1 =  (1 - i) if nlen == 2 else  0
                    recon = trans_splits[ir1].GetReconcile()

                trans_date = transaction.GetDate()
                hu = trans_date.strftime('%m/%d')
                huyr = trans_date.strftime('%m/%d/%Y')
                f = trans_split.GetValue().to_double()
                fs =  "{:,.2f}".format(f)
                tot += f
                tots = "{:,.2f}".format(tot)
                descr = transaction.GetDescription()
                # recon = trans_split.GetReconcile()

                entered_time64 = transaction.GetDateEntered()

                o = {
                    'hu' : hu,
                    'd'  : descr,
                    'nm' : aname,
                    'r'  : recon,
                    'f'  : f,
                    'bal' : round(tot, 2),
                    'huyr' : huyr,
                    'Upost' : int(trans_date.timestamp()),
                    'i' : iacct,
                    'Ucre' : int(entered_time64.timestamp()),
                }

                iacct += 1
                list.append(o)

                if not asj :
                    print(hu, descr , aname, recon, fs, tots)

        if asj:
            json.dump(list, sys.stdout, indent=4)
            print()
