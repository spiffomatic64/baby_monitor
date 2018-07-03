#!/usr/bin/env bash

while :
do
    echo "Checking screen"
    fpissue=1
    sigissue=0

    while [ $fpissue -ne "0" ]
    do
        fpga_ctl t|grep "Horizontal fp, bp: 220, "  &> /dev/null
        fpissue=$?
        echo $fpissue
        if [ $fpissue -ne "0" ]; then
            echo "Screen is not right, fixing..."
            fpga_ctl t|grep "Field time in pixels: 0"  &> /dev/null
            sigissue=$?
            echo $sigissue
            if [ $sigissue -ne "0" ]; then
                fpga_ctl H
                sleep 0.5
                fpga_ctl h
                sleep 5
            else
                echo "no signal, sleeping..."
                fpissue=0
            fi
        else
            echo "Fixed"
        fi
    done
    sleep 1
done
