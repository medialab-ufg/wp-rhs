#!/bin/bash
start=`date +%s`

BASE_DIR=`pwd`/../
SCRIPTS_DIR=`pwd`
PROJECT_DIR=${BASE_DIR}/public/

cd "$PROJECT_DIR"

wp db query <  "$SCRIPTS_DIR"/posts.sql







end=`date +%s`


runtime=$((end-start))

echo "Runtime: $runtime"
