import re
import re

def cek_kodepos(kodepos):
    if len(kodepos) != 5 or not kodepos.isdigit():
        print("Tidak Valid")
        return
    
   
    if int(kodepos) < 10000 or int(kodepos) > 99999:
        print("Tidak Valid")
        return
    
    skip_pairs = 0
    for i in range(len(kodepos) - 2):
        if kodepos[i] == kodepos[i+2] and kodepos[i] != kodepos[i+1]:
            skip_pairs += 1
            if skip_pairs > 1:
                print("Tidak Valid")
                return
    
    if re.search(r'(\d)\1\1\1', kodepos):
        print("Tidak Valid")
        return
    
    print("Valid")


cek_kodepos('12145')  
cek_kodepos('32432')  
cek_kodepos('55511')  
cek_kodepos('55155')
cek_kodepos('55151')  
 
