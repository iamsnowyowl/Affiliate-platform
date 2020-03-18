import React, { Component } from "react";
import Axios from "axios";
import { Link } from "react-router-dom";
import { Steps } from "antd";
import { Card, CardBody, Col, Row, Button, Alert, Label } from "reactstrap";
import {
  AvForm,
  AvField,
  AvGroup,
  AvInput,
  AvFeedback
} from "availity-reactstrap-validation";
import LoadingOverlay from "react-loading-overlay";
import InputData_UnitCompetence from "./InputData_UnitCompetence";
import { Digest } from "../../containers/Helpers/digest";
import { baseUrl, path_schema, formatCapitalize } from "../config/config";
import { multiLanguage } from "../Language/getBahasa";
import LabelRequired from "../Label/LabelRequired";

const { Step } = Steps;

const steps = [
  {
    title: "Form sub Schema"
  },
  {
    title: "Form Unit Kompetence"
  }
];

class InputData_SubSchema extends Component {
  constructor(props) {
    super(props);
    this.state = {
      step: 1,
      current: 0,
      data: {
        sub_schema_number: "",
        sub_schema_name: "",
        schema_id: "",
        schema_name: "",
        skkni: "",
        skkk_year: ""
      },
      inputDepartment: false,
      message: "",
      hidden: true,
      loading: false,
      payload: [],
      bahasa: ""
    };
  }

  componentDidMount() {
    const authentication = Digest(path_schema, "GET");
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        "X-Lsp-Date": authentication.date,
        "Content-Type": "application/json"
      },
      url: baseUrl + path_schema + "?limit=100",
      data: null
    };

    Axios(options).then(request => {
      this.setState({
        payload: request.data.data
      });
    });
  }

  // procced to next step
  nextStep = () => {
    // this.setState({
    //   loading: true
    // });
    const {
      schema_id,
      sub_schema_number,
      sub_schema_name,
      skkni,
      skkk_year,
      files
    } = this.state;
    var formData = new FormData();
    formData.append("sub_schema_number", formatCapitalize(sub_schema_number));
    formData.append("sub_schema_name", formatCapitalize(sub_schema_name));
    formData.append("skkni", formatCapitalize(skkni));
    formData.append("skkk_year", skkk_year);
    formData.append("template", files);
    if (
      sub_schema_number === undefined ||
      sub_schema_name === undefined ||
      schema_id === undefined
    ) {
      this.setState({
        hidden: false,
        message: "Terdapat Field yang kosong,Harap Cek Kembali",
        loading: false
      });
    } else {
      const authentication = Digest(
        path_schema + "/" + schema_id + "/sub_schemas",
        "POST"
      );
      const options = {
        method: authentication.method,
        headers: {
          Authorization: authentication.digest,
          "X-Lsp-Date": authentication.date,
          "Content-Type": "multipart/form-data"
        },
        url: baseUrl + path_schema + "/" + schema_id + "/sub_schemas",
        data: formData
      };
      Axios(options)
        .then(response => {
          if (response.data.responseStatus === "SUCCESS") {
            const { step, current } = this.state;
            this.setState({
              step: step + 1,
              current: current + 1
            });
          }
        })
        .catch(error => {
          let responseJSON = error.response;
          this.setState({
            response: responseJSON.data.error.code
          });
          switch (this.state.response) {
            case 400:
              this.setState({
                hidden: false,
                loading: false,
                message: multiLanguage.alertWrongInput
              });
              break;

            case 409:
              this.setState({
                hidden: false,
                loading: false,
                message: multiLanguage.alertAlready
              });
              break;

            default:
              break;
          }
        });
    }
  };
  // Go back to prev step
  prevStep = value => {
    console.log("back", value);
    const { step, current } = this.state;
    this.setState({
      step: step - 1,
      current: current - 1
    });
  };

  // Handle fields change
  handleChange = event => {
    this.setState({
      [event.target.name]: event.target.value
    });
  };

  render() {
    const { step, current } = this.state;
    const { sub_schema_number, sub_schema_name } = this.state;
    const values = {
      sub_schema_number,
      sub_schema_name
    };
    return (
      <div>
        <Card>
          <CardBody>
            <Steps current={current}>
              {steps.map(item => (
                <Step key={item.title} title={item.title} />
              ))}
            </Steps>
            <p />
            <div className="steps-content" style={{ marginTop: "60px" }}>
              {step === 1 ? (
                <LoadingOverlay
                  active={this.state.loading}
                  spinner
                  text="Loading..."
                >
                  <div>
                    <AvForm
                      action=""
                      encType="multipart/form-data"
                      className="form-horizontal"
                    >
                      <AvGroup row>
                        <Col md="2">
                          <LabelRequired
                            fors="schema_id"
                            label={multiLanguage.mainSchema}
                          />
                        </Col>
                        <Col xs="12" md="3">
                          <AvField
                            type="select"
                            name="schema_id"
                            onChange={this.handleChange}
                            required
                          >
                            <option value="">{multiLanguage.select}</option>
                            {this.state.payload.map(
                              ({ schema_id, schema_name }) => {
                                return (
                                  <option value={schema_id}>
                                    {schema_name}
                                    {""}
                                  </option>
                                );
                              }
                            )}
                          </AvField>
                        </Col>
                        <Col md="2">
                          {" "}
                          <Label for="skkni">{`SKKNI / SKKK/ ${multiLanguage.year}`}</Label>
                        </Col>
                        <Col xs="12" md="2">
                          <AvInput
                            type="text"
                            id="skkni"
                            name="skkni"
                            placeholder="0"
                            onChange={this.handleChange}
                          />
                        </Col>
                        <Col md="auto" style={{ marginTop: "1%" }}>
                          /
                        </Col>
                        <Col xs="12" md="2" style={{ marginTop: "7px" }}>
                          <AvInput
                            type="number"
                            id="skkk_year"
                            name="skkk_year"
                            placeholder="20xx"
                            onChange={this.handleChange}
                          />
                        </Col>
                      </AvGroup>
                      <AvGroup row>
                        <Col md="2">
                          <LabelRequired
                            fors="sub_schema_number"
                            label={multiLanguage.subSchemaCode}
                          />
                        </Col>
                        <Col xs="12" md="3">
                          <AvInput
                            type="text"
                            id="sub_schema_number"
                            name="sub_schema_number"
                            placeholder="xxxxxx"
                            onChange={this.handleChange}
                            maxLength="40"
                            required
                          />
                          <AvFeedback> {multiLanguage.alertField}</AvFeedback>
                        </Col>
                        <Col md="2">
                          <LabelRequired
                            fors="sub_schema_name"
                            label={multiLanguage.subSchemaName}
                          />
                        </Col>
                        <Col xs="12" md="3">
                          <AvField
                            type="text"
                            id="sub_schema_name"
                            name="sub_schema_name"
                            onChange={this.handleChange}
                            errorMessage={multiLanguage.alertField}
                            validate={{
                              required: { value: true }
                            }}
                          />
                          <AvFeedback> {multiLanguage.alertField}</AvFeedback>
                        </Col>
                      </AvGroup>
                      <Alert
                        color="danger"
                        hidden={this.state.hidden}
                        className="text-center"
                      >
                        {this.state.message}
                      </Alert>

                      <Row>
                        <Col md="6">
                          <Link to="/schema/sub-schema">
                            <Button type="submit" size="md" color="danger">
                              <i className="fa fa-chevron-left" />{" "}
                              {multiLanguage.back}
                            </Button>
                          </Link>
                        </Col>
                        <Col md="6">
                          <Button
                            className="btn btn-success Btn-Submit float-md-right"
                            color="success"
                            size="md"
                            type="submit"
                            onClick={this.nextStep}
                            disabled={
                              this.state.sub_schema_number === undefined ||
                              this.state.sub_schema_name === undefined
                                ? true
                                : false
                            }
                          >
                            <i className="fa fa-chevron-right" /> Lanjut
                          </Button>
                        </Col>
                      </Row>
                    </AvForm>
                  </div>
                </LoadingOverlay>
              ) : step === 2 ? (
                <InputData_UnitCompetence
                  nextStep={this.nextStep}
                  prevStep={this.prevStep}
                  values={values}
                />
              ) : (
                ""
              )}
            </div>
          </CardBody>
        </Card>
      </div>
    );
  }
}

export default InputData_SubSchema;
