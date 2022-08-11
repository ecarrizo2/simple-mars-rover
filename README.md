# PHP Mars-Rover Kata

The objective of this project is just about working with tdd and solving a code puzzle for Interviews/Skills demonstrations.

Credits to: https://github.com/codurance/katalyst-kickstart as is where I got the Skeleton from.
Just small improvements over.

You can read more about this kata definition at: https://katalyst.codurance.com/simple-mars-rover

## Getting started

To get everything ready run the following commands:

1. `chmod +x setup.sh`
2. `./setup.sh`

If everything works ok, you should see as output the test running on green.


## How to test the execution

To run the tests use the command `./phpunit test` at the root directory

# Summary of program parts:
- The Program is Divided in 4 pieces:
  - **Rover Folder** contains Interface as described in the Kata is the entry point of the program to execute the instructions to move and turn the rover.
  - **Command Folder** contains helper classes to convert strings representation of a combinations of steps to move the rover into actual usable and typed objects which allows more control and better hanlding over the command step cases.
  - **Grid Folder** contains helper enumeration classes with the different values related to the grid world such as the cardinal points and the Axis configuration.
  - **Navigation** The Rover needs a Navigation Device which keeps track of the position of the Rover and Can provide back instructions so the rover can move it mechanic parts to accomplish the required moves
