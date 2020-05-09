from math import gcd
import binascii
import os

if __name__ == '__main__':
    my_path = os.path.abspath(os.path.dirname(__file__))
    textpath1 =  my_path+'\plaintexts\\test1.txt'
    textpath2 = my_path+'/plaintexts\\test2.txt'
    keypath1 = my_path+'/ciphered\\test1.txt'
    keypath2 = str(my_path+'/ciphered\\test2.txt')

    with open(textpath1, 'rb') as content_file:
        text1 = int(binascii.hexlify(content_file.read()), 16)

    with open(textpath2, 'rb') as content_file:
        text2 = int(binascii.hexlify(content_file.read()), 16)

    with open(keypath1, 'rb') as content_file:
        key1 = int(binascii.hexlify(content_file.read()), 16)

    with open(keypath2, 'rb') as content_file:
        key2 = int(binascii.hexlify(content_file.read()), 16)

    e = 65537

    num1A = pow(text1,e)
    num1B = key1;
    diff1 = num1A-num1B;

    num2A = pow(text2,e);
    num2B = key2;
    diff2 = num2A- num2B;

    mod = gcd(diff1,diff2);
    print(mod)
    exit()