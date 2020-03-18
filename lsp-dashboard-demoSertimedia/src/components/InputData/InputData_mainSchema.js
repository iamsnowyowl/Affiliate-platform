import React, { Component } from "react";
import { Redirect } from "react-router-dom";
import {
  AvForm,
  AvGroup,
  AvFeedback,
  AvInput
} from "availity-reactstrap-validation";
import {
  Button,
  Card,
  CardBody,
  CardHeader,
  Col,
  Label,
  Alert,
  Row
} from "reactstrap";
import "../../css/loaderComponent.css";
import { Digest } from "../../containers/Helpers/digest";
import axios from "axios";
import { Link } from "react-router-dom";
import { baseUrl, path_schema, formatCapitalize } from "../config/config";
import { multiLanguage } from "../Language/getBahasa";
import AvField from "availity-reactstrap-validation/lib/AvField";
import LabelRequired from "../Label/LabelRequired";
import LoadingOverlay from "react-loading-overlay";

const url = baseUrl + path_schema;

class InputData_mainSchema extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        skkni: "",
        skkni_year: "",
        schema_name: ""
      },
      inputFaculty: false,
      loading: false,
      message: "",
      hidden: true,
      payload: []
    };
  }

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleSubmit = (event, errors, values) => {
    this.setState({
      loading: true
    });
    console.log("tes");
    event.preventDefault();

    if (
      this.state.skkni === undefined ||
      this.state.skkni_year === undefined ||
      this.state.schema_name === undefined
    ) {
      this.setState({
        hidden: false,
        message: multiLanguage.alertInput,
        inputFaculty: false,
        loading: false
      });
    } else {
      this.setState({ errors, values });
      this.setState({ fireRedirect: true });
      var formData = new FormData();

      const authentication = Digest(path_schema, "POST");
      formData.append("skkni", formatCapitalize(this.state.skkni));
      formData.append("skkni_year", formatCapitalize(this.state.skkni_year));
      formData.append("schema_name", formatCapitalize(this.state.schema_name));
      formData.append("total_uk", formatCapitalize(this.state.total_uk));

      const options = {
        method: authentication.method,
        headers: {
          Authorization: authentication.digest,
          "X-Lsp-Date": authentication.date,
          "Content-Type": "multipart/form-data"
        },
        url: url,
        data: formData
      };
      axios(options)
        .then(response => {
          console.log(response);
          this.setState({ inputFaculty: true });
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
                message: multiLanguage.alertInput,
                loading: false
              });
              break;

            case 409:
              this.setState({
                hidden: false,
                message: multiLanguage.alertAlready,
                loading: false
              });
              break;

            default:
              break;
          }
        });
    }
  };

  render() {
    if (this.state.inputFaculty) {
      return <Redirect to={"/schema/main-schema"} />;
    }
    const { skkni, skkni_year, schema_name } = this.state;
    return (
      <div className="animated facdeIn">
        <LoadingOverlay active={this.state.loading} spinner text="Loading">
          <Card>
            <CardHeader
              style={{ textAlign: "center" }}
            >{`${multiLanguage.add} Data`}</CardHeader>
            <CardBody>
              <AvForm
                encType="multipart/form"
                className="form-horizontal"
                onSubmit={this.handleSubmit}
              >
                <AvGroup row>
                  <Col md="2">
                    <LabelRequired fors="skkni" label="SKKNI/Thn" />
                  </Col>
                  <Col xs="4" md="2">
                    <AvField
                      type="text"
                      id="skkni"
                      name="skkni"
                      placeholder="SKKNI"
                      onChange={this.handleChange}
                      errorMessage={multiLanguage.alertField}
                      defaultValue={skkni}
                      validate={{
                        required: { value: true }
                      }}
                    />
                  </Col>
                  <Col md="0" style={{ marginTop: "10px" }}>
                    /
                  </Col>
                  <Col xs="4" md="2">
                    <AvField
                      type="number"
                      id="skkni_year"
                      name="skkni_year"
                      placeholder={multiLanguage.year}
                      onChange={this.handleChange}
                      errorMessage={multiLanguage.alertField}
                      defaultValue={skkni_year}
                      validate={{
                        required: { value: true }
                      }}
                    />
                    <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="2">
                    <LabelRequired
                      fors="schema_name"
                      label={multiLanguage.name}
                    />
                  </Col>
                  <Col xs="12" md="4">
                    <AvField
                      type="text"
                      id="schema_name"
                      name="schema_name"
                      onChange={this.handleChange}
                      errorMessage={multiLanguage.alertField}
                      defaultValue={schema_name}
                      validate={{
                        required: { value: true }
                      }}
                    />
                  </Col>
                  <Col md="2">
                    <Label htmlFor="total_uk">{multiLanguage.totalUnit}</Label>
                  </Col>
                  <Col xs="12" md="2">
                    <AvInput
                      type="number"
                      id="total_uk"
                      name="total_uk"
                      placeholder="0"
                      onChange={this.handleChange}
                    />
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
                  <Col md="1.5">
                    <Link to={"/schema/main-schema"}>
                      <Button type="submit" size="md" color="danger">
                        <i className="fa fa-close" /> {multiLanguage.cancel}
                      </Button>
                      <p />
                    </Link>
                  </Col>
                  <Col md="1.5" className="Btn-Submit">
                    <Button
                      className="btn btn-success Btn-Submit"
                      color="success"
                      size="md"
                      type="submit"
                    >
                      <i className="fa fa-check" /> {multiLanguage.submit}
                    </Button>
                  </Col>
                </Row>
              </AvForm>
            </CardBody>
          </Card>
        </LoadingOverlay>
      </div>
    );
  }
}

export default InputData_mainSchema;
