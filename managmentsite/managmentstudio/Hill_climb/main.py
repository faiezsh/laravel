# This is a sample Python script.

# Press Shift+F10 to execute it or replace it with your code.
# Press Double Shift to search everywhere for classes, files, tool windows, actions, a!
import Pieces
import Scale
import DistributionAlgorithm

if __name__ == '__main__':
    a = Scale.Scale()
    a.setXYZ(1.0, 1.0, 1.0)

    room_scale = Scale.Scale()
    room_scale.setXYZ(4, 3, 4)

    washer = Pieces.Pieces()
    washer.setPropertise(a, "floor")

    table = Pieces.Pieces()
    table.setPropertise(a, "floor")

    Array = [table]

    #

    room1 = DistributionAlgorithm.distribute(room_scale, Array, [], [])

    room1.Printer()
# See PyCharm help at https://www.jetbrains.com/help/pycharm/
