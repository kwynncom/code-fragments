import subprocess

def get_shell_output(command):
    try:
        result = subprocess.check_output(command, shell=True, text=True).strip()
        if result.isdigit() and 1 <= len(result) <= 2:
            return result
        else:
            raise ValueError("Command output is not a 1- or 2-digit number")
    except subprocess.CalledProcessError:
        raise RuntimeError("Command execution failed")
