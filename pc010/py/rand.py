# This is not my work.  I am running this to compare my version in PHP to this.

from random import randint
#range 1-100
def guessing_game():
    random_number = randint(1, 100)
    #get input guess from user.
    while True:
        guess = int(input('Guess the number between 1-100 or enter 0 to quit:'))
        if guess == 0:
            print('See ya!')
            break
        elif guess == random_number:
            print('You win! Let\'s play again :)')
            random_number = randint(1, 100)
        elif guess < random_number:
            print('Too low! Try again!')
        elif guess > random_number:
            print('Too high! Try again!')


guessing_game()
