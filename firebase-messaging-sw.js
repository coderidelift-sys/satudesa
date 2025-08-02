// firebase-messaging-sw.js
importScripts("https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js");

const firebaseConfig = {
  apiKey: "AIzaSyDZqRRV_2D1oMh6poCaHDU5J8n2XCnmMJw",
  authDomain: "pwanotif-9dea1.firebaseapp.com",
  projectId: "pwanotif-9dea1",
  storageBucket: "pwanotif-9dea1.firebasestorage.app",
  messagingSenderId: "1043147027293",
  appId: "1:1043147027293:web:74162206e485f7fe552eb4",
  measurementId: "G-C4J2CFC8MC"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
  console.log("Received background message ", payload);

  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: payload.notification.icon
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
