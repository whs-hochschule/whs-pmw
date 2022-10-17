import numpy as np
from argparse import ArgumentParser

parser = ArgumentParser()
parser.add_argument('-p', '--periode', dest="periode")
parser.add_argument('-n', '--nachfrage', dest="nachfrage")
args = parser.parse_args()

array1 = args.periode.split(',')
array1 = np.array(array1, dtype=np.int64)
array2 = args.nachfrage.split(',')
array2 = np.array(array2, dtype=np.int64)

print(np.corrcoef(array1, array2)[0][1])