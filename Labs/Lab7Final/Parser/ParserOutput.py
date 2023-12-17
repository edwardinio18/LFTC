from tabulate import tabulate

from DS.Node import Node


class ParserOutput:
    def __init__(self, grammar, sequence_file, out_file):
        self.grammar = grammar
        self.out_file = out_file
        self.sequence = self.read_sequence(sequence_file)
        self.tree = []

    @staticmethod
    def read_sequence(seq_file):
        seq = []
        with open(seq_file) as f:
            line = f.readline()
            while line:
                seq.append(line.strip())
                line = f.readline()
        return seq

    def create_parsing_tree(self, working):
        father = -1
        for index in range(0, len(working)):
            if isinstance(working[index], tuple):
                self.tree.append(Node(working[index][0]))
                self.tree[index].production = working[index][1]
            else:
                self.tree.append(Node(working[index]))

        for index in range(0, len(working)):
            if isinstance(working[index], tuple):
                self.tree[index].father = father
                father = index
                len_prod = len(self.grammar.get_productions()[working[index][0]][working[index][1]])
                vec_idx = []
                for i in range(1, len_prod + 1):
                    vec_idx.append(index + i)
                for i in range(0, len_prod):
                    if self.tree[vec_idx[i]].production != -1:
                        offset = self.get_len_depth(vec_idx[i], working)
                        for j in range(i + 1, len_prod):
                            vec_idx[j] += offset
                for i in range(0, len_prod - 1):
                    self.tree[vec_idx[i]].sibling = vec_idx[i + 1]
            else:
                self.tree[index].father = father
                father = -1

    def get_len_depth(self, index, working):
        production = self.grammar.get_productions()[working[index][0]][working[index][1]]
        len_prod = len(production)
        s = len_prod
        for i in range(1, len_prod + 1):
            if isinstance(working[index + i], tuple):
                s += self.get_len_depth(index + i, working)
        return s

    def write_parsing_tree(self, state, working):
        if state != "e":
            table = [["index", "value", "father", "sibling"]]
            for index in range(0, len(working)):
                table.append([index, self.tree[index].value, self.tree[index].father, self.tree[index].sibling])

            print("Parsing tree:")
            print(tabulate(table, headers="firstrow", tablefmt="grid"))

            with open(self.out_file, "a") as f:
                f.write("\nParsing tree:\n")
                f.write(tabulate(table, headers="firstrow", tablefmt="grid"))
