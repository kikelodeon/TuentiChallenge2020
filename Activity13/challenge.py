from multiprocessing import Pool, cpu_count
import time
import os
import math
import sys
from decimal import *
class Fortress:

  def __init__(self,num):
      self.x = 1
      self.y = 1
      self.z = 3 if(num<(3))else(num)
      self.unitcount = 43 if(num<(3))else (self.CountFromZ(self.z))

  def Expand(self):
      if (self.x==self.y):
          self.unitcount += self.WallDeltaCount(self.x,self.z)
          self.x+=1
      else:
          self.unitcount += self.WallDeltaCount(self.x,self.z)
          self.y+=1

  def Grow(self):
      self.z += 1
      self.x = 1
      self.y = 1
      self.unitcount = self.CountFromZ(self.z)

  def WallDeltaCount(self,size,height):
      return Decimal((size * height) + (2 * (height + 2 * (((height - 2) * (height - 1) / 2) - 1)))).to_integral_exact()

  def CountFromZ(self,xxx):
      xxx = Decimal(xxx)
      t16 = Decimal(16)
      t3 = Decimal(3)
      t12 =Decimal(12)
      t8 = Decimal(8)
      return (((t16*xxx*xxx*xxx) /t3) - (t12*xxx*xxx) - (xxx/t3)+8).to_integral_exact()

class Problem:
    def __init__(self,inputFilePath):
        with open(inputFilePath, 'r') as file:
            text1 = file.read()
        arrayElements1=  text1.split("\n")
        self.cases = arrayElements1
        self.cases.remove("")
        self.result= []


def SolveCase(index,data,problem):
    voxelsRemaining = int(data)
    desiredZ = 0
    desiredCount = 0
    fortress = Fortress(-1)

    while (True):
        currentUC = fortress.unitcount
        if(currentUC>voxelsRemaining):
            break
        fortress.Grow()

    start =fortress.z-1
    fortress= Fortress(start)
    while (True):
        currentVoxelCount = fortress.unitcount
        if (currentVoxelCount > voxelsRemaining and fortress.x == 1 and fortress.y == 1):
            break
        if (currentVoxelCount <= voxelsRemaining and (
                fortress.z > desiredZ or (fortress.z == desiredZ and currentVoxelCount > desiredCount))):
            desiredZ = fortress.z
            desiredCount = currentVoxelCount

        if (currentVoxelCount > voxelsRemaining):
            fortress.Grow()
        else:
            fortress.Expand()

    result = (str(desiredZ) + " " + str(int(desiredCount))) if (desiredZ > 0) else "IMPOSSIBLE"
    print("Case #"+str(index) + ": " + result)
    caseResult = CaseResult(index,result)
    problem.result.append(caseResult)
    return caseResult
class CaseResult:
    def __init__(self,index,result):
        self.index = index
        self.result = result




if __name__ == '__main__':
    my_path = os.path.abspath(os.path.dirname(__file__))
    testInputPath =  my_path+'\\testInput'
    problem = Problem(testInputPath)
    numberofcases = len(problem.cases)


    for x in range(1, numberofcases):
        SolveCase(x,problem.cases[x],problem)



    exit()


