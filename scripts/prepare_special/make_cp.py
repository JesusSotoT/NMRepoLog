
import sys
dirs = []
instance = ""

if len(sys.argv) == 0:
    instance = raw_input("Instance name:");
    dirs.append(instance)
else:
    for arg in sys.argv:
        if arg!="make_cp.py":
            dirs.append(arg)

#print dirs

for dir in dirs:
    instance = dir
    file = open("list_files.txt")
    print "\necho '[%s]'" % instance
    for line in file.readlines():
        file_row=line[0:-1];
        print "cp _base/"+file_row+" "+instance+"/"+file_row
    file.close()
