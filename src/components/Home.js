import React from "react";
import "../components/Home.css";

function Home() {
  return (
    <div className="Home1">
      <header>
        <div className="tit">
          <h1>
            BUYING YOUR <br></br> PRODUCTS{" "}
            <b
              style={{
                color: "green",
                fontSize: "100px",
                fontFamily: "initial",
              }}
            >
              SHOPPING
            </b>
            <br></br>
            <span>
              <p className="subtit">
                (safe,secure,reliable buying.)
              </p>
            </span>
          </h1>
        </div>
      </header>
    </div>
  );
}

export default Home;
