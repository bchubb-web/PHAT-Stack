import os
pages = os.listdir('./pages/')

routesFile = open('./routes.php', 'w')

toWrite = '''<?php
get('/', 'pages/home.php');

'''


def findVars(listOfLines: list) -> list | bool:
    for line in listOfLines:
        if line[:2] == '//':
            line = line[2:].strip()
            vars = line.split(',')
            return list(map(lambda var: var.strip(), vars))
    return False


def makeRoute(route: str, page: str) -> str:
    return f"get('/{route}', 'pages/{page}');\n\n"


for page in pages:
    print(page)
    routeBase = page.split('.')[0]
    toWrite += makeRoute(routeBase, page)
    rawFile = open('./pages/'+page, 'r')
    vars = findVars(list(rawFile))
    rawFile.close()
    print(vars)
    if vars:
        for i in range(0, len(vars)):
            semiRoute = '/'.join(vars[:i+1])
            toWrite += makeRoute(routeBase+'/'+semiRoute, page)

routesFile.write(toWrite)
routesFile.close()
