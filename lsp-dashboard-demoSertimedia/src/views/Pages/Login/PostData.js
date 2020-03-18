export function PostData(type,data) {
  let config = 'http://192.168.10.10/api/users/';

  return new Promise((resolve,reject) =>{
    fetch(config+type,{
      method: 'POST',
      body: JSON.stringify(data)
    })
    .then((response) => response.json())
    .then((responseJson) => {
      resolve(responseJson);
    })
    .catch((error) => {
      reject();
    });
  });
}
