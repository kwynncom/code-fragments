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
    ''' Grok's commentary '''
    """
    Retrieve splits for a specified account from a GNUCash file.

    Args:
        gnucash_file_path (str): Path to the GNUCash file.
        account_name (str): Name of the account to retrieve splits for.

    Returns:
        list: List of splits for the specified account, or None if account not found.
    """
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