import React, { Component } from "react";
import { Row, Col, Button, Alert } from "reactstrap";
import { Redirect } from "react-router-dom";

import { List, ListItem } from "material-ui/List";
import { multiLanguage } from "../Language/getBahasa";
import { MuiThemeProvider } from "material-ui/styles";
import { Divider } from "antd";

import "antd/dist/antd.css";
import {
  path_accessors,
  path_applicant,
  path_management,
  path_users,
  baseUrl
} from "../config/config";
import { Digest } from "../../containers/Helpers/digest";
import Axios from "axios";
import LoadingOverlay from "react-loading-overlay";

class FormPersonalDetail extends Component {
  constructor(props) {
    super(props);
    this.state = {
      inputData: false,
      loading: false,
      hidden: true,
      message: ""
    };
  }

  back = event => {
    event.preventDefault();
    this.props.prevStep();
  };

  handleSubmit = (event, errors, values) => {
    const {
      values: {
        username,
        email,
        first_name,
        last_name,
        contact,
        gender_code,
        place_of_birth,
        date_of_birth,
        address,
        role_code,
        nik,
        npwp,
        tuk_id,
        institution,
        registration_number,
        level,
        jobs,
        pendidikan_terakhir
      }
    } = this.props;

    this.setState({ errors, values, loading: true });
    event.preventDefault();
    var path = path_users;

    this.setState({ fireRedirect: true });
    // this.props.history.push('/addusers');
    var formData = new FormData();
    // var imagefile = document.querySelector('#picture');
    // formData.append('picture', imagefile.files[0]);

    formData.append("username", username);
    formData.append("email", email);
    formData.append("first_name", first_name);
    formData.append("last_name", last_name);
    formData.append("contact", contact);
    formData.append("gender_code", gender_code);
    formData.append("place_of_birth", place_of_birth);
    formData.append("date_of_birth", date_of_birth);
    formData.append("address", address);
    formData.append("role_code", role_code);
    switch (role_code) {
      case "ACS":
        path += path_accessors;
        formData.append("nik", nik);
        formData.append("npwp", npwp);
        formData.append("registration_number", registration_number);
        break;

      case "APL":
        // var portofolio = JSON.parse(JSON.stringify(file.base64));
        path += path_applicant;
        formData.append("nik", nik);
        formData.append("npwp", npwp);
        formData.append("tuk_id", tuk_id);
        formData.append("institution", institution);
        formData.append("jobs_code", jobs.key);
        formData.append("pendidikan_terakhir", pendidikan_terakhir);
        break;

      case "ADT":
        path += "/admintuk";
        formData.append("tuk_id", tuk_id);
        break;

      case "MAG":
        path += path_management;
        formData.append("level", level);
        break;

      default:
        break;
    }

    const authentication = Digest(path, "POST");
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        "X-Lsp-Date": authentication.date,
        "Content-Type": "multipart/form-data"
      },
      url: baseUrl + path,
      data: formData
    };
    Axios(options)
      .then(response => {
        if (response.data.responseStatus === "SUCCESS") {
          this.setState({ inputData: true, loading: false });
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        this.setState({
          response: responseJSON.data.error.code,
          loading: false
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
    if (this.state.inputData) {
      return <Redirect to={"/users"} />;
    }

    const {
      values: {
        username,
        email,
        first_name,
        last_name,
        contact,
        gender_code,
        place_of_birth,
        date_of_birth,
        address,
        role_code,
        nik,
        npwp,
        tuk_id,
        institution,
        registration_number,
        payloadTuk,
        level,
        hiddenAlert,
        messageAlert,
        jobs,
        pendidikan_terakhir
      }
    } = this.props;

    var tuk = [];
    var tuk_name = "";
    if (role_code === "APL" || role_code === "ADT") {
      tuk = payloadTuk.filter(item => item.tuk_id === tuk_id);
      tuk_name = tuk[0].tuk_name;
    }

    console.log("alert", jobs);
    return (
      <LoadingOverlay active={this.state.loading} spinner text="Loading...">
        <MuiThemeProvider>
          <React.Fragment>
            <Divider orientation="left">Account Details</Divider>
            <Row>
              <Col>
                <List>
                  <ListItem
                    style={
                      username !== ""
                        ? { backgroundColor: "#aae5a7" }
                        : { backgroundColor: "#fae2e0" }
                    }
                    primaryText="Username"
                    secondaryText={username}
                  />
                </List>
              </Col>
              <Col>
                <List>
                  <ListItem
                    style={
                      email !== ""
                        ? { backgroundColor: "#aae5a7" }
                        : { backgroundColor: "#fae2e0" }
                    }
                    primaryText="Email"
                    secondaryText={email}
                  />
                </List>
              </Col>
              <Col>
                <List>
                  <ListItem
                    style={{ backgroundColor: "#aae5a7" }}
                    primaryText="Role"
                    secondaryText={
                      role_code === "DEV"
                        ? "Developer"
                        : role_code === "SUP"
                        ? "Super User"
                        : role_code === "MAG"
                        ? "Management"
                        : role_code === "ADM"
                        ? "Admin LSP"
                        : role_code === "ADT"
                        ? "Admin TUK"
                        : role_code === "ACS"
                        ? multiLanguage.assessors
                        : role_code === "APL"
                        ? multiLanguage.asesi
                        : ""
                    }
                  />
                </List>
              </Col>
            </Row>
            <Divider orientation="left">Personal Details</Divider>
            <Row>
              <Col>
                <List>
                  <ListItem
                    style={
                      first_name !== ""
                        ? { backgroundColor: "#aae5a7" }
                        : { backgroundColor: "#fae2e0" }
                    }
                    primaryText={multiLanguage.name}
                    secondaryText={first_name + " " + last_name}
                  />
                </List>
              </Col>
              {role_code === "ACS" ? (
                <Col>
                  <List>
                    <ListItem
                      style={
                        nik !== ""
                          ? { backgroundColor: "#aae5a7" }
                          : { backgroundColor: "#fae2e0" }
                      }
                      primaryText="NIK"
                      secondaryText={nik}
                    />
                  </List>
                </Col>
              ) : role_code === "APL" ? (
                <Col>
                  <List>
                    <ListItem
                      style={
                        nik !== ""
                          ? { backgroundColor: "#aae5a7" }
                          : { backgroundColor: "#fae2e0" }
                      }
                      primaryText="NIK"
                      secondaryText={nik}
                    />
                  </List>
                </Col>
              ) : (
                ""
              )}
            </Row>
            <Row>
              <Col>
                <List>
                  <ListItem
                    style={
                      date_of_birth !== ""
                        ? { backgroundColor: "#aae5a7" }
                        : { backgroundColor: "#fae2e0" }
                    }
                    primaryText={multiLanguage.dateOfBirth}
                    secondaryText={date_of_birth}
                  />
                </List>
              </Col>
              {role_code === "ACS" ? (
                ""
              ) : role_code === "APL" ? (
                <Col>
                  <List>
                    <ListItem
                      style={
                        npwp !== ""
                          ? { backgroundColor: "#aae5a7" }
                          : { backgroundColor: "#fae2e0" }
                      }
                      primaryText="NPWP"
                      secondaryText={npwp}
                    />
                  </List>
                </Col>
              ) : (
                ""
              )}
            </Row>
            <Row>
              <Col>
                <List>
                  <ListItem
                    style={
                      place_of_birth === "" || place_of_birth.length < 3
                        ? { backgroundColor: "#fae2e0" }
                        : { backgroundColor: "#aae5a7" }
                    }
                    primaryText={multiLanguage.placeBirth}
                    secondaryText={place_of_birth}
                  />
                </List>
              </Col>
              {role_code === "ACS" ? (
                <Col>
                  <List>
                    <ListItem
                      style={
                        registration_number !== ""
                          ? { backgroundColor: "#aae5a7" }
                          : { backgroundColor: "#fae2e0" }
                      }
                      primaryText="No.Registrasi"
                      secondaryText={registration_number}
                    />
                  </List>
                </Col>
              ) : role_code === "APL" ? (
                <Col>
                  <List>
                    <ListItem
                      style={
                        jobs.label !== ""
                          ? { backgroundColor: "#aae5a7" }
                          : { backgroundColor: "#fae2e0" }
                      }
                      primaryText={multiLanguage.jobs}
                      secondaryText={jobs.label}
                    />
                  </List>
                </Col>
              ) : (
                ""
              )}
            </Row>
            <Row>
              <Col>
                <List>
                  <ListItem
                    style={
                      contact === "" || contact.length < 6
                        ? { backgroundColor: "#fae2e0" }
                        : { backgroundColor: "#aae5a7" }
                    }
                    primaryText={multiLanguage.contact}
                    secondaryText={contact}
                  />
                </List>
              </Col>
              <Col>
                <List>
                  <ListItem
                    style={
                      gender_code !== ""
                        ? { backgroundColor: "#aae5a7" }
                        : { backgroundColor: "#fae2e0" }
                    }
                    primaryText={multiLanguage.gender}
                    secondaryText={
                      gender_code === "M"
                        ? multiLanguage.male
                        : gender_code === "F"
                        ? multiLanguage.female
                        : "-"
                    }
                  />
                </List>
              </Col>
            </Row>
            <Row>
              <Col>
                <List>
                  <ListItem
                    style={
                      address === "" || address.length < 3
                        ? { backgroundColor: "#fae2e0" }
                        : { backgroundColor: "#aae5a7" }
                    }
                    primaryText={multiLanguage.address}
                    secondaryText={address}
                  />
                </List>
              </Col>
            </Row>{" "}
            {role_code === "APL" ? (
              <div>
                <Row>
                  <Col>
                    <List>
                      <ListItem
                        style={
                          tuk_name !== ""
                            ? { backgroundColor: "#aae5a7" }
                            : { backgroundColor: "#fae2e0" }
                        }
                        primaryText={multiLanguage.education}
                        secondaryText={pendidikan_terakhir}
                      />
                    </List>
                  </Col>
                </Row>
                <Row>
                  <Col>
                    <List>
                      <ListItem
                        style={
                          tuk_name !== ""
                            ? { backgroundColor: "#aae5a7" }
                            : { backgroundColor: "#fae2e0" }
                        }
                        primaryText="TUK"
                        secondaryText={tuk_name}
                      />
                    </List>
                  </Col>
                  <Col>
                    <List>
                      <ListItem
                        style={
                          institution === "" || institution.length < 3
                            ? { backgroundColor: "#fae2e0" }
                            : { backgroundColor: "#aae5a7" }
                        }
                        primaryText={multiLanguage.institute}
                        secondaryText={institution}
                      />
                    </List>
                  </Col>
                </Row>
              </div>
            ) : role_code === "ADT" ? (
              <Row>
                <Col>
                  <List>
                    <ListItem
                      style={
                        tuk_name !== ""
                          ? { backgroundColor: "#aae5a7" }
                          : { backgroundColor: "#fae2e0" }
                      }
                      primaryText="TUK"
                      secondaryText={tuk_name}
                    />
                  </List>
                </Col>
              </Row>
            ) : role_code === "MAG" ? (
              <Row>
                <Col>
                  <List>
                    <ListItem
                      style={
                        level !== ""
                          ? { backgroundColor: "#aae5a7" }
                          : { backgroundColor: "#fae2e0" }
                      }
                      primaryText="Posisi"
                      secondaryText={level}
                    />
                  </List>
                </Col>
              </Row>
            ) : (
              ""
            )}
            <Row>
              <Col>
                {hiddenAlert === true ? (
                  <Alert
                    color="danger"
                    hidden={this.state.hidden}
                    className="text-center"
                  >
                    {this.state.message}
                  </Alert>
                ) : (
                  <Alert
                    color="danger"
                    hidden={hiddenAlert}
                    className="text-center"
                  >
                    {messageAlert}
                  </Alert>
                )}
              </Col>
            </Row>
            <Row>
              <Col md="1">
                <Button
                  className="btn btn-success Btn-Submit"
                  color="danger"
                  size="md"
                  type="submit"
                  onClick={this.back}
                >
                  {multiLanguage.back}
                </Button>
              </Col>
              <Col md="1.5">
                <Button
                  className="btn btn-success Btn-Submit"
                  color="success"
                  size="md"
                  type="submit"
                  onClick={this.handleSubmit}
                  hidden={!hiddenAlert ? true : false}
                >
                  <i className="fa fa-check" /> {multiLanguage.submit}
                </Button>
              </Col>
            </Row>
          </React.Fragment>
        </MuiThemeProvider>
      </LoadingOverlay>
    );
  }
}

export default FormPersonalDetail;
