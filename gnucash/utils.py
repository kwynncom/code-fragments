# utils.py

from gnucash import GncNumeric, Session, SessionOpenMode

def find_account_by_name(root_account, name):
    if root_account.name == name:
        return root_account
    for child in root_account.get_children():
        found = find_account_by_name(child, name)
        if found:
            return found
    return None


def get_account_splits(gnucash_file_path, account_name):
    try:
        with Session(gnucash_file_path, SessionOpenMode.SESSION_READ_ONLY) as session:
            root_account = session.book.get_root_account()
            account = find_account_by_name(root_account, account_name)
            if account:
                return account.GetSplitList()
            return None
    except Exception as e:
        print(f"Error accessing GNUCash file or account: {e}", file=sys.stderr)
        return None

class SplitData:
    pass  # Blank object

def get_to_splits(gnucash_file_path, account_name):

    list = []
    ilist = 0
    runningBalance = 0

    splits = get_account_splits(gnucash_file_path, account_name)

    for baseSplit in splits:

        transaction = baseSplit.GetParent()
        trans_splits = transaction.GetSplitList()
        nTransSplits = len(trans_splits)

        for iTransSplits, trans_split in enumerate(trans_splits):

            acctName = trans_split.GetAccount().GetName()

            if acctName == account_name:
               continue  # I'm not interested in the credit card side of the split; I want to know where the money went
            else : # but I do want I want the reconciled flag from the credit card side
                ir1 =  (1 - iTransSplits) if nTransSplits == 2 else  0
                reconciled = trans_splits[ir1].GetReconcile()

            amount = trans_split.GetValue().to_double()
            runningBalance += amount

            o = SplitData()
            o.acctName = acctName
            o.amount = amount
            o.balance = runningBalance
            o.reconciled = reconciled
            o.split = trans_split
            o.transaction = transaction
            o.i = ilist

            list.append(o)
            ilist += 1

    return list