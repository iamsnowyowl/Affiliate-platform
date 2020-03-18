importScripts('https://www.gstatic.com/firebasejs/5.5.3/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.5.3/firebase-messaging.js');
var config = {
  apiKey: 'AIzaSyABSJ9PyirICy9cpi-J0U_iW9OD9FIjXrQ',
  authDomain: 'sertimedia-565f9.firebaseapp.com',
  databaseURL: 'https://sertimedia-565f9.firebaseio.com',
  projectId: 'sertimedia-565f9',
  storageBucket: '',
  messagingSenderId: '885128320535',
  appId: '1:885128320535:web:e2f8ccded0c2a08d'
};
firebase.initializeApp(config);

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/firebase-logo.png'
  };

  return self.registration.showNotification(
    notificationTitle,
    notificationOptions
  );
});
