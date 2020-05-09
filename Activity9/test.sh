#!/bin/sh
chr() {
        printf \\$(printf '%03o' $1)
    }
    
hex() {
        printf '%02X\n' $1
    }
encrypt() {
        key=$1
        msg=$2
        crpt_msg=""
        for ((i=0; i<${#msg}; i++)); do
            c=${msg:$i:1}
            asc_chr=$(echo -ne "$c" | od -An -tuC)
            key_pos=$((${#key} - 1 - ${i}))
	        key_char=${key:$key_pos:1}          
            crpt_chr=$(( $asc_chr ^ ${key_char} ))
            hx_crpt_chr=$(hex $crpt_chr)
            crpt_msg=${crpt_msg}${hx_crpt_chr}
        done
        echo $crpt_msg
    }

#counter 1111111111111
counter=$1
message=$2

 key=$( encrypt $counter $message)
 echo $key;

# key=""
# while [ "$key" != "3633363A33353B393038383C363236333635313A353336" ] || [ "$counter" -gt "0" ]
# do
  
#    counter=$(( $counter - 1 ))
#    echo $key
#    echo "\\"
# done
# echo "FOUND"
# echo $key




