import React, { Component } from "react";
import { Card, CardHeader, CardBody, Row, Col } from "reactstrap";
import { Radio } from "antd";
import { multiLanguage } from "../Language/getBahasa";
import "../../css/loaderComponent.css";
import "antd/dist/antd.css";
import LabelRequired from "../Label/LabelRequired";
import InputDataPortofolioUmum from "./InputDataPortofolioUmum";
import InputDataPortofolioDasar from "./InputDataPortofolioDasar";

export default class InputData_portofolio extends Component {
  constructor(props) {
    super(props);
    this.state = {
      types: 0
    };
  }

  onChange = e => {
    this.setState({
      types: e.target.value
    });
  };

  render() {
    const { types } = this.state;

    return (
      <div>
        <Card>
          <CardHeader
            style={{ textAlign: "center" }}
          >{`${multiLanguage.add} Data`}</CardHeader>
          <CardBody>
            <Row>
              <Col md="2">
                <LabelRequired
                  fors="type"
                  label={multiLanguage.requirementsType}
                />
              </Col>
              <Col xs="5" md="4">
                <Radio.Group onChange={this.onChange} value={types}>
                  <Radio value={0}>Persyaratan Umum</Radio>
                  <Radio value={1}>Persyaratan Dasar</Radio>
                </Radio.Group>
              </Col>
            </Row>
            <p />
            {types === 0 ? (
              <InputDataPortofolioUmum type={types} />
            ) : (
              <InputDataPortofolioDasar type={types} />
            )}
          </CardBody>
        </Card>
      </div>
    );
  }
}
