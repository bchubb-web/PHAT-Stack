import os
pages = os.listdir('./pages/')

routesFile = open('./routes.php', 'w')

toWrite = '''<?php
get('/', 'pages/home.php');

'''


def findVars(listOfLines: list) -> list | bool:
    print(len(listOfLines))
    if len(listOfLines) < 2:
        return False
    if listOfLines[1][:1] == '//':
        line = listOfLines[1][2:].strip()
        return line.split(',').strip()
    return False


def makeRoute(route: str, page: str) -> str:
    return f"get('/{route}', 'pages/{page}');\n\n"


for page in pages:
    routeBase = page.split('.')[0]
    toWrite += makeRoute(routeBase, page)
    rawFile = open('./pages/'+page, 'r')
    vars = findVars(list(rawFile))
    print(type(vars))
    rawFile.close()
    if vars:
        for i in range(0, len(vars)):
            semiRoute = '/'.join(vars[:i+1])
            toWrite += makeRoute(routeBase+'/'+semiRoute, page)

routesFile.write(toWrite)
routesFile.close()
