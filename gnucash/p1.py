import json
import sys # used for sys.stdout / output

GNUCASH_FILE_PATH = '/var/kwynn/gnucash.xml.gnucash'
GNUCASH_ACCT = 'secret25'

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
    with Session(GNUCASH_FILE_PATH, SessionOpenMode.SESSION_READ_ONLY) as session:

        account_to_show = GNUCASH_ACCT

        root_account = session.book.get_root_account()
        account = find_account_by_name(root_account, account_to_show)
        splits = account.GetSplitList()

        iacct = 0
        runningBalance = 0
        list = []

        for split in splits:

            transaction = split.GetParent()
            trans_splits = transaction.GetSplitList()
            for i, trans_split in enumerate(trans_splits):
                recon = '?'
                aname = trans_split.GetAccount().GetName()

                if aname == account_to_show:
                   continue  # I'm not interested in the credit card side of the split; I want to know where the money went
                else : # but I do want I want the reconciled flag from the credit card side
                    nlen = len(trans_splits)
                    ir1 =  (1 - i) if nlen == 2 else  0
                    recon = trans_splits[ir1].GetReconcile()

                trans_date = transaction.GetDate()
                f = trans_split.GetValue().to_double()
                runningBalance += f

                o = {
                    'to': aname,
                    'huDatePosted': trans_date.strftime('%m/%d/%Y'),
                    'amount': f,
                    'bal': round(runningBalance, 2),
                    'descr'  : transaction.GetDescription(),
                    'reconciled'  : recon,
                    'i': iacct,
                    'Uposted'  : int(trans_date.timestamp()),
                    'Ucreated' : int(transaction.GetDateEntered().timestamp()),
                    'splitGUID': split.GetGUID().to_string(),
                }

                iacct += 1
                list.append(o)

    json.dump(list, sys.stdout, indent=4)
    print()

