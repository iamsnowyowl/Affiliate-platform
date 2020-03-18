import { baseUrl, path_users } from '../components/config/config';
export function PostData(type, data) {
  const FETCH_TIMEOUT = 10000;
  let didTimeOut = false;
  let config = baseUrl + path_users;

  return (
    new Promise((resolve, reject) => {
      const timeout = setTimeout(function() {
        didTimeOut = true;
        reject(new Error('Request Time Out'));
      }, FETCH_TIMEOUT);

      fetch(config + type, {
        method: 'POST',
        body: JSON.stringify(data)
      })
        .then(response => 
          response.json()
        )
        .then(responseJson => {
          clearTimeout(timeout);
          if (!didTimeOut) {
            resolve(responseJson);
          }
        })
        .catch(error => {
          if (didTimeOut) return;
          alert('server Offline');
          reject(error);
        });
    })
      // .then(function() {
      // })
      .catch(function(err) {
        alert('Not Internet Connection');
        window.location.reload();
      })
  );
}
