import React, { Component } from "react";
import { Link, Redirect } from "react-router-dom";
import { Button, Row, Col, Label, Alert } from "reactstrap";
import { Checkbox } from "antd";
import { path_masterData, getData, formatCapitalize } from "../config/config";
import {
  AvForm,
  AvField,
  AvGroup,
  AvInput,
  AvFeedback
} from "availity-reactstrap-validation";
import Axios from "axios";

import "../../css/loaderComponent.css";
import "antd/dist/antd.css";
import LoadingOverlay from "react-loading-overlay";
import { multiLanguage } from "../Language/getBahasa";
import LabelRequired from "../Label/LabelRequired";

type Props = {
  type: any
};

class InputDataPortofolioUmum extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        form_name: ""
      },
      payload: [],
      inputPortofolio: false,
      message: "",
      hidden: true,
      subField: true,
      multiple: "0",
      loading: false
    };
  }

  handleChange = (event, errors, values) => {
    this.setState({
      [event.target.name]: event.target.value,
      errors,
      values
    });
  };

  onChange = event => {
    if (event.target.checked === true) {
      this.setState({
        multiple: "1"
      });
    } else {
      this.setState({
        multiple: "0"
      });
    }
  };

  handleSubmit = event => {
    event.preventDefault();
    this.setState({ fireRedirect: true, loadig: true });
    const { type } = this.props;

    var formData = new FormData();
    var documentArray_state = "ALL".split();
    formData.append("type", type === 0 ? "UMUM" : "DASAR");
    formData.append("form_type", "file");
    formData.append("form_name", formatCapitalize(this.state.form_name));
    formData.append("is_multiple", this.state.multiple);
    formData.append("document_state", documentArray_state);
    formData.append("sub_schema_number", "");

    console.log(documentArray_state);

    Axios(getData(path_masterData, "POST", formData))
      .then(response => {
        if (response.data.responseStatus === "SUCCESS") {
          this.setState({ inputPortofolio: true });
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        this.setState({
          loading: false,
          response: responseJSON.data.error.code
        });

        switch (this.state.response) {
          case 400:
            this.setState({
              hidden: false,
              message: multiLanguage.alertInput
            });
            break;

          case 409:
            this.setState({
              hidden: false,
              message: multiLanguage.alertAlready
            });
            break;

          default:
            break;
        }
      });
  };
  render() {
    if (this.state.inputPortofolio) {
      return <Redirect to={"/portfolios"} />;
    }
    // const {defaultValues} = this.props
    return (
      <LoadingOverlay active={this.state.loadig} spinner text="Loading..">
        <div>
          <AvForm
            action=""
            encType="multipart/form-data"
            className="form-horizontal"
            // model={defaultValues}
          >
            <AvGroup row>
              <Col md="2">
                <LabelRequired
                  fors="form_name"
                  label={multiLanguage.formName}
                />
              </Col>
              <Col xs="5" md="3">
                <AvInput
                  type="text"
                  style={{ borderColor: "black" }}
                  id="form_name"
                  name="form_name"
                  placeholder={multiLanguage.formName}
                  onChange={this.handleChange}
                  required
                />
                <AvFeedback>{multiLanguage.alertField}</AvFeedback>
              </Col>
              <Col md="2">{`${multiLanguage.multipleFile} `}</Col>
              <Col md="auto">
                <Checkbox onChange={this.onChange} />{" "}
                {this.state.multiple !== "1" ? "Tidak" : "Ya"}
              </Col>
            </AvGroup>
            <Alert
              color="danger"
              hidden={this.state.hidden}
              className="text-center"
            >
              {this.state.message}
            </Alert>
          </AvForm>
          <p />
          <Row>
            <Col md="1.5">
              <Link to={path_masterData}>
                <Button type="submit" size="md" className="btn-danger">
                  <i className="fa fa-chevron-left" /> {multiLanguage.back}
                </Button>
              </Link>
            </Col>
            <Col md="1.5">
              <Button
                className="btn btn-success Btn-Submit"
                type="submit"
                size="md"
                color="success"
                onClick={this.handleSubmit}
              >
                <i className="fa fa-check" /> {multiLanguage.submit}
              </Button>
            </Col>
          </Row>
        </div>
      </LoadingOverlay>
    );
  }
}

export default InputDataPortofolioUmum;
