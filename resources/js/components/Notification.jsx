import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import firebase from 'firebase/app';
import 'firebase/messaging';

const firebaseConfig = {
    apiKey: "AIzaSyCCPnwQmruMkG__JJ78apEHbhoiFIUokv0",
    authDomain: "exo-cp.firebaseapp.com",
    databaseURL: "https://exo-cp.firebaseio.com",
    projectId: "exo-cp",
    storageBucket: "exo-cp.appspot.com",
    messagingSenderId: "588173249396",
    appId: "1:588173249396:web:1dc39f6b5a1f494bf3ba21",
    measurementId: "G-3E1T1W7SMC"
};

  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);

  if ("serviceWorker" in navigator) {
    navigator.serviceWorker
      .register("./js/firebase-messaging-sw.js")
      .then(function(registration) {
        console.log("Registration successful, scope is:", registration.scope);
      })
      .catch(function(err) {
        console.log("Service worker registration failed, error:", err);
      });
  }

  const messaging = firebase.messaging();
  messaging.onMessage((payload) => {
    console.log('Message received. ', payload);
  });

export default class Notification extends Component {
    constructor() {
        super();
    }

    componentDidMount() {
        try {
                console.log("angefordert 2");
                const messaging = firebase.messaging();
                messaging.requestPermission();
                const token = messaging.getToken();
                    axios.post("/api/notification", {token: token, user_id: 1})
                        .then(response => {
                            console.log(response.data);
                        })
                        .catch(error => {
                            console.log(error.response);
                        });
                console.log(token);
                return token;
            } catch (error) {
              console.error(error);
            }
    }

    render() {
        return (
           <div>
           </div>
        );
    }
}

var notification = document.getElementsByTagName('react-notification');

for (var index in notification) {
    const component = notification[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<Notification {...props} />, component);
    }
}

