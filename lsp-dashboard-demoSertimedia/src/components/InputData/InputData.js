import React, { Component } from "react";
import { Card, CardBody, CardHeader } from "reactstrap";
import "../../css/loaderComponent.css";
import { multiLanguage } from "../Language/getBahasa";
import UserForm from "../StepsForm/UserForm";
class InputData extends Component {
  render() {
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader style={{ textAlign: "center" }}>
            {multiLanguage.add} Data
          </CardHeader>
          <CardBody>
            <UserForm />
          </CardBody>
        </Card>
      </div>
    );
  }
}

export default InputData;
