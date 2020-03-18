import React, { Component } from "react";
import { Redirect } from "react-router-dom";
import moment from "moment";
import {
  AvForm,
  AvField,
  AvGroup,
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
  Input,
  Row
} from "reactstrap";
import LoadingOverlay from "react-loading-overlay";
import PlacesAutocomplete, {
  geocodeByAddress,
  getLatLng,
  geocodeByPlaceId
} from "react-places-autocomplete";
import axios from "axios";
import { Link } from "react-router-dom";

import "../../css/mandatoryAlert.css";
import "../../css/Activity.css";
import { multiLanguage } from "../Language/getBahasa";
import {
  path_assessments,
  getData,
  path_tuk,
  path_schemaViews,
  baseUrl,
  formatCapitalize
} from "../config/config";
import { Digest } from "../../containers/Helpers/digest";
import LabelRequired from "../Label/LabelRequired";

const pathSchema = "/public" + path_schemaViews;

class InputData_Assessment extends Component {
  sigPad = {};
  constructor(props) {
    super(props);
    this.state = {
      data: {
        title: "",
        address: "",
        notes: "",
        latitude: "",
        longitude: "",
        start_date: null,
        end_date: null,
        tuk_id: "",
        sub_schema_number: ""
      },
      modal: false,
      message: "",
      longitude: "",
      latitude: "",
      inputActivity: false,
      hidden: true,
      payload: [],
      payloadTUK: [],
      payloadSubSchema: [],
      payloadSchema: [],
      response: "",
      address: "",
      hiddenClear: true,
      loadingOverlay: false
    };
    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleChange = this.handleChange.bind(this);
  }

  toggle = () => {
    this.setState({
      modal: !this.state.modal
    });
  };

  Get(options, response) {
    axios(options).then(res => {
      this.setState({
        [response]: res.data.data
      });
    });
  }

  async componentDidMount() {
    const auth = Digest(pathSchema, "GET");
    var link = baseUrl + pathSchema + "?limit=100";

    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: link
    };

    const authTUK = Digest(path_tuk, "GET");
    var linkTUK = baseUrl + path_tuk + "?limit=100";

    const optionsTUK = {
      method: authTUK.method,
      headers: {
        Authorization: authTUK.digest,
        "X-Lsp-Date": authTUK.date,
        "Content-Type": "multipart/form-data"
      },
      url: linkTUK
    };
    await this.Get(optionsTUK, "payloadTUK");
    this.Get(options, "payloadSchema");
  }

  handleChangePlace = address => {
    this.setState({
      address,
      latitude: null,
      longitude: null
    });
  };

  handleChange = event => {
    this.setState({
      [event.target.name]: event.target.value
    });
  };

  handleSubmit = (_event, errors, values) => {
    const {
      title,
      sub_schema_number,
      start_date,
      end_date,
      latitude,
      longitude,
      address,
      tuk_id,
      response,
      notes
    } = this.state;
    this.setState({
      fireRedirect: true,
      errors,
      values,
      loadingOverlay: true
    });
    if (
      title === undefined ||
      sub_schema_number === undefined ||
      start_date === undefined ||
      end_date === undefined ||
      latitude === undefined ||
      longitude === undefined ||
      address === undefined ||
      tuk_id === undefined
    ) {
      this.setState({
        hidden: false,
        message: multiLanguage.alertInput,
        loadingOverlay: false
      });
    } else if (title.length < 3 || address.length < 3) {
      this.setState({
        hidden: false,
        message: multiLanguage.alertErrorField,
        loadingOverlay: false
      });
    } else {
      var start = moment(start_date).format();
      var end = moment(end_date).format();
      var formData = new FormData();
      formData.append("title", formatCapitalize(title));
      formData.append("sub_schema_number", formatCapitalize(sub_schema_number));
      formData.append("address", formatCapitalize(address));
      formData.append("notes", formatCapitalize(notes));
      formData.append("latitude", latitude);
      formData.append("longitude", longitude);
      formData.append("start_date", start);
      formData.append("end_date", end);
      formData.append("tuk_id", tuk_id);
      formData.append("last_activity_state", "ADMIN_CONFIRM_FORM");
      axios(getData(path_assessments, "POST", formData))
        .then(response => {
          if (response.data.responseStatus === "SUCCESS") {
            this.setState({ inputActivity: true, loadingOverlay: false });
          }
        })
        .catch(error => {
          let responseJSON = error.response;
          this.setState({
            response: responseJSON.data.error.code
          });
          switch (response) {
            case 400:
              this.setState({
                hidden: false,
                message: multiLanguage.alertInput,
                loadingOverlay: false
              });
              break;

            case 409:
              this.setState({
                hidden: false,
                message: responseJSON.data.error.message,
                loadingOverlay: false
              });
              break;

            default:
              break;
          }
        });
    }
  };

  handleSelect = (address, placeId) => {
    geocodeByAddress(address)
      .then(results => getLatLng(results[0]))
      .then(({ lat, lng }) => {
        this.setState({
          latitude: lat,
          longitude: lng,
          address: address
        });
      })
      .catch(error => console.error("Error", error));

    geocodeByPlaceId(placeId)
      .then(results => getLatLng(results[0]))
      .then(({ lat, lng }) => {
        this.setState({
          latitude: lat,
          longitude: lng,
          address: address
        });
      })
      .catch(error => console.log("error"));
  };

  clear = () => {
    this.sigPad.clear();
  };

  render() {
    if (this.state.inputActivity) {
      return <Redirect to={path_assessments + "/list"} />;
    }
    return (
      <div className="animated fadeIn">
        <LoadingOverlay
          active={this.state.loadingOverlay}
          spinner
          text="Loading..."
        >
          <Card>
            <CardHeader style={{ textAlign: "center" }}>
              {multiLanguage.add} {multiLanguage.Assessment}
            </CardHeader>
            <CardBody>
              <AvForm onSubmit={this.handleSubmit}>
                <AvGroup row>
                  <Col md="2">
                    <LabelRequired
                      fors="title"
                      label={multiLanguage.assessmentName}
                    />
                  </Col>
                  <Col xs="5" md="4">
                    <AvField
                      type="text"
                      id="title"
                      name="title"
                      onChange={this.handleChange}
                      validate={{
                        required: {
                          value: true,
                          errorMessage: " "
                        },
                        minLength: {
                          value: 3,
                          errorMessage: multiLanguage.minCharacter
                        }
                      }}
                    />
                  </Col>
                  <Col md="2">
                    <LabelRequired
                      fors="sub_schema_number"
                      label={multiLanguage.schemaName}
                    />
                  </Col>
                  <Col xs="5" md="4">
                    <AvField
                      type="select"
                      name="sub_schema_number"
                      onChange={this.handleChange}
                      validate={{
                        required: {
                          value: true,
                          errorMessage: " "
                        }
                      }}
                    >
                      <option value="">{`${multiLanguage.select} ${multiLanguage.schema}`}</option>
                      {this.state.payloadSchema.map(
                        ({ sub_schema_number, sub_schema_name }) => {
                          return (
                            <option
                              value={sub_schema_number}
                              key={sub_schema_number}
                            >
                              {sub_schema_name}
                            </option>
                          );
                        }
                      )}
                    </AvField>
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="2">
                    <LabelRequired
                      fors="Competence"
                      label={multiLanguage.startDate}
                    />
                  </Col>
                  <Col xs="5" md="4">
                    <AvInput
                      type="date"
                      id="start_date"
                      name="start_date"
                      onChange={this.handleChange}
                      validate={{
                        required: {
                          value: true,
                          errorMessage: " "
                        }
                      }}
                    />
                  </Col>
                  <Col md="2">
                    <LabelRequired fors="title" label={multiLanguage.endDate} />
                  </Col>
                  <Col xs="5" md="4">
                    <AvInput
                      type="date"
                      id="end_date"
                      name="end_date"
                      onChange={this.handleChange}
                      validate={{
                        required: {
                          value: true,
                          errorMessage: " "
                        }
                      }}
                    />
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="2">
                    {`${multiLanguage.address} ${multiLanguage.Assessment}`}
                  </Col>
                  <Col xs="5" md="4">
                    <PlacesAutocomplete
                      id="address"
                      name="address"
                      value={this.state.address}
                      onChange={this.handleChangePlace}
                      onSelect={this.handleSelect}
                      onClick={this.handleSelect}
                    >
                      {({
                        getInputProps,
                        suggestions,
                        getSuggestionItemProps,
                        loading
                      }) => (
                        <div>
                          <Input
                            type="text"
                            id="address"
                            name="address"
                            onChange={this.handleChange}
                            {...getInputProps({
                              placeholder: `${multiLanguage.search} ${multiLanguage.Assessment}..`,
                              className: "location-search-input"
                            })}
                          />
                          <div className="autocomplete-dropdown-container">
                            {loading && <div>Loading...</div>}
                            {suggestions.map(suggestion => {
                              const className = suggestion.active
                                ? "suggestion-item--active"
                                : "suggestion-item";
                              const style = suggestion.active
                                ? {
                                    backgroundColor: "#fafafa",
                                    cursor: "pointer"
                                  }
                                : {
                                    backgroundColor: "#ffffff",
                                    cursor: "pointer"
                                  };
                              return (
                                <div
                                  {...getSuggestionItemProps(suggestion, {
                                    className,
                                    style
                                  })}
                                >
                                  <span>{suggestion.description}</span>
                                </div>
                              );
                            })}
                          </div>
                        </div>
                      )}
                    </PlacesAutocomplete>
                  </Col>
                  <Col md="2">
                    <LabelRequired fors="tuk_id" label="TUK" />
                  </Col>
                  <Col xs="5" md="4">
                    <AvField
                      type="select"
                      id="tuk_id"
                      name="tuk_id"
                      onChange={this.handleChange}
                      validate={{
                        required: {
                          value: true,
                          errorMessage: " "
                        }
                      }}
                    >
                      <option value="">{multiLanguage.select} TUK</option>
                      {this.state.payloadTUK.map(
                        ({ tuk_id, tuk_name }, _key) => {
                          return (
                            <option value={tuk_id} key={tuk_id}>
                              {tuk_name}
                            </option>
                          );
                        }
                      )}
                    </AvField>
                  </Col>
                </AvGroup>
                <AvGroup row>
                  <Col md="2">
                    <Label htmlFor="notes">{multiLanguage.note}</Label>
                  </Col>
                  <Col xs="12" md="10">
                    <AvInput
                      type="textarea"
                      id="notes"
                      name="notes"
                      placeholder="optional"
                      rows="5"
                      onChange={this.handleChange}
                    />
                  </Col>
                </AvGroup>
                <Row>
                  <Col>
                    <Alert
                      color="danger"
                      hidden={this.state.hidden}
                      className="text-center"
                    >
                      {this.state.message}
                    </Alert>
                  </Col>
                </Row>
                <Row>
                  <Col
                    md="0.5"
                    style={{ marginLeft: "13px", marginRight: "5px" }}
                  >
                    <Link to={path_assessments + "/list"}>
                      <Button
                        className="btn btn-danger"
                        style={{ width: "116%" }}
                      >
                        <i className="fa fa-close"> {multiLanguage.cancel}</i>
                      </Button>
                    </Link>
                  </Col>{" "}
                  <Col>
                    <Button className="btn btn-success">
                      {" "}
                      <i className="fa fa-check"> {multiLanguage.submit}</i>
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

export default InputData_Assessment;
