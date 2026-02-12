// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyC5kX5l7rZSeYHOJWyKaWnK4zkeUIt1uUE",
    authDomain: "rezervist-ff34f.firebaseapp.com",
    projectId: "rezervist-ff34f",
    storageBucket: "rezervist-ff34f.firebasestorage.app",
    messagingSenderId: "557970594286",
    appId: "1:557970594286:web:36044141135367d056d6ff",
    measurementId: "G-5KCNPQY5NZ"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

export { app, analytics };
