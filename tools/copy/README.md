Once baking is completed, some additional code may have to be copied over. Use this folder to accomplish that.

### Copy whole files from `./files`

Contents of this folder will be copied over to `PLUGIN/src/Model`. If there is literal `${VER}` in filenames and namespaces, it will be replaced with actual `${VER}` passed to, and available inside `bake.sh`.

Useful for adding shared models, or models for tables not present in the database.