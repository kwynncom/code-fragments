GNUCASH_FILE_PATH = '/var/kwynn/gnucash.xml.gnucash'
GNUCASH_ACCT_NAME_TO_SHOW = 'secret25'

import json
import sys # used for sys.stdout / output
from utils import get_account_splits

if __name__ == '__main__':

    splits = get_account_splits(GNUCASH_FILE_PATH, GNUCASH_ACCT_NAME_TO_SHOW)

    iShown = 0
    runningBalance = 0
    list = []

    for baseSplit in splits:

        transaction = baseSplit.GetParent()
        trans_splits = transaction.GetSplitList()
        nTransSplits = len(trans_splits)

        for iTransSplits, trans_split in enumerate(trans_splits):
            reconciled = '?'
            acctName = trans_split.GetAccount().GetName()

            if acctName == GNUCASH_ACCT_NAME_TO_SHOW:
               continue  # I'm not interested in the credit card side of the split; I want to know where the money went
            else : # but I do want I want the reconciled flag from the credit card side
                ir1 =  (1 - iTransSplits) if nTransSplits == 2 else  0
                reconciled = trans_splits[ir1].GetReconcile()

            trans_date = transaction.GetDate()
            amount = trans_split.GetValue().to_double()
            runningBalance += amount

            o = {
                'to': acctName,
                'huDatePosted': trans_date.strftime('%m/%d/%Y'),
                'amount': amount,
                'bal': round(runningBalance, 2),
                'descr'  : transaction.GetDescription(),
                'reconciled'  : reconciled,
                'i': iShown,
                'Uposted'  : int(trans_date.timestamp()),
                'Ucreated' : int(transaction.GetDateEntered().timestamp()),
                'splitGUID': trans_split.GetGUID().to_string(),
            }

            iShown += 1
            list.append(o)

    json.dump(list, sys.stdout, indent=4)
    print()

