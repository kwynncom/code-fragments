GNUCASH_FILE_PATH = '/var/kwynn/gnucash/gnucash.xml.gnucash'
GNUCASH_FOCUS_ACCT_GUID_FILE = '/var/kwynn/gnucash/focus_guid.txt'

import json
import sys # used for sys.stdout / output
from utils import get_to_splits

if __name__ == '__main__':

    list = []

    splits = get_to_splits(GNUCASH_FILE_PATH, GNUCASH_FOCUS_ACCT_GUID_FILE)

    for o in splits:

            trans_date = o.transaction.GetDate()

            listo = {
                'to': o.acctName,
                'foAcctName' : o.foAcctName,
                'foAcctGUID' : o.foAcctGUID,
                'huDatePosted': trans_date.strftime('%m/%d/%Y'),
                'amount': o.amount,
                'bal': round(o.balance, 2),
                'descr'  : o.transaction.GetDescription(),
                'reconciled'  : o.reconciled,
                'i': o.i,
                'Uposted'  : int(trans_date.timestamp()),
                'Ucreated' : int(o.transaction.GetDateEntered().timestamp()),
                'splitGUID': o.split.GetGUID().to_string(),
            }

            list.append(listo)

    json.dump(list, sys.stdout, indent=4)
    print()

