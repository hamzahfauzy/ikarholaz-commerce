import { initializeApp } from 'firebase/app';
import { getAuth, RecaptchaVerifier,signInWithPhoneNumber } from "firebase/auth";

// TODO: Replace the following with your app's Firebase project configuration
const firebaseConfig = {
    apiKey: "AIzaSyB21_ri_UxG93I05uKZT0hiworkhgvv4tE",
    authDomain: "ika-mboyz.firebaseapp.com",
    projectId: "ika-mboyz",
    storageBucket: "ika-mboyz.appspot.com",
    messagingSenderId: "863671288485",
    appId: "1:863671288485:web:509e820b7b197aa54f434c",
    measurementId: "G-T4QEFMK92D"
};

window.firebaseapp = initializeApp(firebaseConfig);
window.firebaseauth = {getAuth, RecaptchaVerifier, signInWithPhoneNumber}
// window.auth = getAuth();
// window.recaptchaVerifier = RecaptchaVerifier
// window.recaptchaVerifier = new RecaptchaVerifier('recaptcha-container', {
//     'size': 'normal',
//     'callback': () => {
//         // reCAPTCHA solved, allow signInWithPhoneNumber.
//         this.login()
//     }
// }, auth);
// window.recaptchaVerifier.render().then((widgetId) => {
//     window.recaptchaWidgetId = widgetId;
// });