// db.createUser(
//     {
//         user: _getEnv("DB_USERNAME"),
//         pwd: _getEnv("DB_PASSWORD"),
//         roles: [
//             {
//                 role: "readWrite",
//                 db:  _getEnv("MONGO_INITDB_DATABASE")
//             }
//         ]
//     }
// );

db.createCollection('delete_me');
