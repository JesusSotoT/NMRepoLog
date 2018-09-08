
#instance = raw_input("Instance name:");
instance="/srv/www/construccion/mlog/"
file = open("list_files.txt")
for line in file.readlines():
    file_row=line[0:-1];
    print "cp /srv/www/htdocs/clientes/_base/"+file_row+" "+instance+file_row

file.close()
