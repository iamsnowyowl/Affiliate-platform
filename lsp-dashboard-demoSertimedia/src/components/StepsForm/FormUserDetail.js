import React, { Component } from "react";
import { Row, Col, Button } from "reactstrap";
import { AvForm, AvField, AvGroup } from "availity-reactstrap-validation";
import { multiLanguage } from "../Language/getBahasa";
import LabelRequired from "../Label/LabelRequired";
import { path_users } from "../config/config";

class FormUserDetail extends Component {
  continue = event => {
    event.preventDefault();
    this.props.nextStep();
  };

  handleBack = () => {
    this.setState({
      loading: true
    });
    window.history.back();
  };

  render() {
    const { values, handleChange } = this.props;
    const role = localStorage.getItem("role");
    return (
      <React.Fragment>
        <AvForm encType="multipart/form-data" className="form-horizintal">
          <AvGroup row>
            <Col md="2">
              <LabelRequired fors="username" label="Username" />
            </Col>
            <Col xs="12" md="3">
              <AvField
                type="text"
                name="username"
                defaultValue={values.username}
                onChange={handleChange("username")}
                validate={{
                  required: {
                    value: true,
                    errorMessage: multiLanguage.alertName
                  }
                }}
              />
            </Col>
            <Col md="1">
              <LabelRequired fors="email" label="Email" />
            </Col>
            <Col xs="5" md="3">
              <AvField
                type="text"
                defaultValue={values.email}
                name="email"
                onChange={handleChange("email")}
                validate={{
                  required: {
                    value: true,
                    errorMessage: multiLanguage.alertName
                  }
                }}
              />
            </Col>
          </AvGroup>
          <AvGroup row>
            <Col md="2">
              <LabelRequired fors="role_code" label={multiLanguage.role} />
            </Col>
            <Col xs="12" md="7">
              {role === "DEV" ? (
                <AvField
                  type="select"
                  name="role_code"
                  onChange={handleChange("role_code")}
                  defaultValue={values.role_code}
                  validate={{
                    required: {
                      value: true,
                      errorMessage: multiLanguage.alertName
                    }
                  }}
                >
                  <option value="">
                    {multiLanguage.select} {multiLanguage.role}
                  </option>
                  <option value="DEV">Developer</option>
                  <option value="SUP">Super Admin</option>
                  <option value="ADM">Admin LSP</option>
                  <option value="MAG">Management</option>
                  <option value="ADT">Admin TUK</option>
                  <option value="ACS">{multiLanguage.assessors}</option>
                  <option value="APL">{multiLanguage.asesi}</option>
                </AvField>
              ) : role === "SUP" ? (
                <AvField
                  type="select"
                  name="role_code"
                  onChange={handleChange("role_code")}
                  defaultValue={values.role_code}
                  validate={{
                    required: {
                      value: true,
                      errorMessage: multiLanguage.alertName
                    }
                  }}
                >
                  <option value="">
                    {multiLanguage.select} {multiLanguage.role}
                  </option>
                  <option value="ADM">Admin LSP</option>
                  <option value="MAG">Management</option>
                  <option value="ADT">Admin TUK</option>
                  <option value="ACS">{multiLanguage.assessors}</option>
                  <option value="APL">{multiLanguage.asesi}</option>
                </AvField>
              ) : (
                <AvField
                  type="select"
                  name="role_code"
                  onChange={handleChange("role_code")}
                  defaultValue={values.role_code}
                  validate={{
                    required: {
                      value: true,
                      errorMessage: multiLanguage.alertName
                    }
                  }}
                >
                  <option value="">
                    {multiLanguage.select} {multiLanguage.role}
                  </option>
                  <option value="MAG">Management</option>
                  <option value="ADT">Admin TUK</option>
                  <option value="ACS">{multiLanguage.assessors}</option>
                  <option value="APL">{multiLanguage.asesi}</option>
                </AvField>
              )}
            </Col>
          </AvGroup>
          <Row>
            <Col md="6">
              <Button
                type="submit"
                size="md"
                color="danger"
                onClick={this.handleBack}
              >
                <i className="fa fa-chevron-left" /> {multiLanguage.back}
              </Button>
            </Col>
            <Col md="6">
              <Button
                className="btn btn-success Btn-Submit float-md-right"
                color="success"
                size="md"
                type="submit"
                onClick={this.continue}
                disabled={
                  values.role_code === "" ||
                  values.username === "" ||
                  values.email === ""
                    ? true
                    : false
                }
              >
                {multiLanguage.continue}
              </Button>
            </Col>
          </Row>
        </AvForm>
      </React.Fragment>
    );
  }
}

export default FormUserDetail;
