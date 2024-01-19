import React from "react";
import "./Contact.css";

function Contact() {
  return (
    <div className="body2">
      <form>
        <div>
          <label>Name:</label>
          <input type="text" />
        </div>

        <div>
          <label>Email:</label>
          <input type="email" />
        </div>

        <div>
          <label>Message:</label>
          <textarea name="message" />
        </div>

        <div>
          <button type="#"> Send Message</button>
        </div>
      </form>
    </div>
  );
}

export default Contact;