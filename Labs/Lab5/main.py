from Grammar import Grammar

if __name__ == '__main__':
    g = Grammar()
    g.read_from_file("g1.in")
    print(str(g))
    if g.checkCFG():
        print("The grammar is a CFG")
    else:
        print("The grammar is not a CFG")
