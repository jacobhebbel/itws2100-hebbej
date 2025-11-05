# Lab6 Readme
### Jacob Hebbel

## Questions

### How could I avoid if statements?
I could use a switch statement. Additionally, with some reformatting changes, I would be able to use polymorphism to cast an object into a subclass based on the inputs.

### Explain every class and every method and order of execution
Because the methods are templated, it makes sense to just explain the base class as the others just extend it
- operate: executes an equation
- getEquation: string representation of the equation executed
- Each class represents an operation

As for control flow, the following happens:
1. the server receives a post request, and parses it into op1/op2
2. an object is initialized based on a req.body arg
3. The php script representing the result uses the obj methods to give a UI for the equation

### What would happen if you used GET? Would this be better?
Using GET would be a disadvantage. Semantically it does not make sense, as the request pushes data to the server whereas a GET does not add something.

### Is there a better way to track button presses / data?
I would use the fetch library to make AJAX-style calls when textboxes are updated and buttons are pressed. This would be a more responsive UI and while being a bit more complicated, would be easily integratable into many different systems.

