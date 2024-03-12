### IDEAS

stack example, linked through database, so anyone can see the state of hte stack.
will persist over time.

db wrapper, will handle login info from config file, but takes databse name / table in class init/ static start
will create database if doesnt exist, can have templates to follow, users etc





namespace router

test if route exists as namespace, if not then start popping off a part into a 
new stack (dynamic namespace parts) and replace with "Var", which can then 
handle what to do with the dynamic parts, most likely use first value as 
variable and then rest as hard data
