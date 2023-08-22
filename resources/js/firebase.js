// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getMessaging, getToken, onMessage } from "firebase/messaging";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyAzTk6l-mnT5Ww_cKZntva9qTfS1x72kA8",
  authDomain: "test-project-eaacb.firebaseapp.com",
  projectId: "test-project-eaacb",
  storageBucket: "test-project-eaacb.appspot.com",
  messagingSenderId: "510488086312",
  appId: "1:510488086312:web:e0795884699a943b21321d",
  measurementId: "G-93W7NZ21WS"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

// Get registration token. Initially this makes a network call, once retrieved
// subsequent calls to getToken will return from cache.
const messaging = getMessaging();
getToken(messaging, { vapidKey: 'BFYrOPFYuDxJ3G3Et0lGVLDuzQQkqjS37ofDzsFeGgpwjX-1rP8ENWnCo8GtzQXDVhcHLQO8u4GdO9AjhBfOhPw' }).then((currentToken) => {
  if (currentToken) {
    // Send the token to your server and update the UI if necessary
    console.log(currentToken);
  } else {
    // Show permission request UI
    console.log('No registration token available. Request permission to generate one.');
    // ...
  }
}).catch((err) => {
  console.log('An error occurred while retrieving token. ', err);
  // ...
});

onMessage(messaging, (payload) => {
  console.log('Message received. ', payload);
  // ...
});

