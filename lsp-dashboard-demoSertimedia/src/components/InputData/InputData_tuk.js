import React, { Component } from "react";
import { Redirect } from "react-router-dom";
import {
  AvForm,
  AvGroup,
  AvFeedback,
  AvField
} from "availity-reactstrap-validation";
import {
  Button,
  Card,
  CardBody,
  CardHeader,
  Col,
  Label,
  Alert,
  Row,
  Input
} from "reactstrap";
import axios from "axios";
import { Link } from "react-router-dom";
import {
  path_HomeTuk,
  getData,
  path_tukAdd,
  formatCapitalize
} from "../../components/config/config";
import "../../css/loaderComponent.css";
import PlacesAutocomplete, {
  geocodeByAddress,
  geocodeByPlaceId,
  getLatLng
} from "react-places-autocomplete";
import AvInput from "availity-reactstrap-validation/lib/AvInput";
import { multiLanguage } from "../Language/getBahasa";
import LabelRequired from "../Label/LabelRequired";

class InputData_tuk extends Component {
  constructor(props) {
    super(props);
    this.state = {
      bahasa: "",
      data: {
        tuk_name: "",
        address: "",
        longitude: "",
        latitude: null,
        contact: null,
        description: "",
        tuk_type: "",
        number_sk: "",
        expired_date: ""
      },
      address: "",
      message: "",
      inputTuk: false,
      hidden: true,
      payload: []
    };
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

  handleSubmit = event => {
    event.preventDefault();
    this.setState({ fireRedirect: true });
    const {
      tuk_name,
      address,
      contact,
      description,
      tuk_type,
      number_sk,
      expired_date,
      longitude,
      latitude,
      response
    } = this.state;
    if (
      tuk_name === undefined ||
      number_sk === undefined ||
      tuk_type === undefined ||
      contact === undefined
    ) {
      this.setState({
        hidden: false,
        message: multiLanguage.alertInput,
        loadingOverlay: false
      });
    } else if (
      tuk_name.length < 3 ||
      number_sk.length < 3 ||
      tuk_type.length < 3 ||
      contact.length < 6
    ) {
      this.setState({
        hidden: false,
        message: multiLanguage.alertErrorField,
        loadingOverlay: false
      });
    } else {
      var formData = new FormData();

      formData.append("tuk_name", formatCapitalize(tuk_name));
      formData.append("address", formatCapitalize(address));
      formData.append("contact", contact);
      formData.append("description", formatCapitalize(description));
      formData.append("tuk_type", tuk_type);
      formData.append("number_sk", number_sk);
      formData.append("expired_date", expired_date);
      formData.append("longitude", longitude);
      formData.append("latitude", latitude);

      axios(getData(path_tukAdd, "POST", formData))
        .then(response => {
          if (response.data.responseStatus === "SUCCESS") {
            this.setState({ inputTuk: true });
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
      });
  };

  handleCloseClick = () => {
    this.setState({
      address: "",
      latitude: null,
      longitude: null
    });
  };

  render() {
    if (this.state.inputTuk) {
      return <Redirect to={"/tuk"} />;
    }
    const { address } = this.state;
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader style={{ textAlign: "center" }}>
            {multiLanguage.add} Data TUK
          </CardHeader>
          <CardBody>
            <AvForm encType="multipart/form-data" className="form-horizintal">
              <AvGroup row>
                <Col md="2">
                  <LabelRequired
                    fors="tuk_name"
                    label={multiLanguage.nameTuk}
                  />
                </Col>
                <Col xs="12" md="3">
                  <AvField
                    type="text"
                    id="tuk_name"
                    name="tuk_name"
                    placeholder={multiLanguage.name}
                    onChange={this.handleChange}
                    validate={{
                      required: {
                        value: true,
                        errorMessage: multiLanguage.alertName
                      },
                      pattern: {
                        value: "^[A-Za-z ]+$",
                        errorMessage: multiLanguage.alertPatternName
                      },
                      minLength: {
                        value: 3,
                        errorMessage: multiLanguage.minCharacter
                      }
                    }}
                  />
                  <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                </Col>
                <Col md="2">
                  <LabelRequired fors="tuk_sk" label={multiLanguage.codeTuk} />
                </Col>
                <Col xs="12" md="3">
                  <AvInput
                    type="text"
                    id="number_sk"
                    name="number_sk"
                    placeholder={multiLanguage.placeholderCodeTUK}
                    onChange={this.handleChange}
                    required
                  />
                  <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="2">
                  <LabelRequired fors="contact" label={multiLanguage.contact} />
                </Col>
                <Col xs="12" md="3">
                  <AvField
                    type="number"
                    id="contact"
                    name="contact"
                    placeholder="08999xxxxxxxx"
                    onChange={this.handleChange}
                    validate={{
                      required: {
                        value: true,
                        errorMessage: multiLanguage.alertContact
                      },
                      pattern: {
                        value: "^[0-9]*$",
                        errorMessage: multiLanguage.alertPattertContact
                      },
                      minLength: {
                        value: 6,
                        errorMessage: multiLanguage.alertMinMaxContact
                      },
                      maxLength: {
                        value: 16,
                        errorMessage: multiLanguage.alertMinMaxContact
                      }
                    }}
                  />
                </Col>
                <Col md="2">
                  <LabelRequired
                    fors="tuk_name"
                    label={multiLanguage.typeTuk}
                  />
                </Col>
                <Col xs="12" md="3">
                  <AvField
                    style={{}}
                    type="select"
                    id="tuk_type"
                    name="tuk_type"
                    onChange={this.handleChange}
                    required
                  >
                    <option value="">
                      {multiLanguage.select} {multiLanguage.type} TUK
                    </option>
                    <option value="mandiri">TUK Mandiri</option>
                    <option value="sewaktu">TUK Sewaktu</option>
                    <option value="tempat_kerja">TUK Tempat Kerja</option>
                  </AvField>
                  <AvFeedback>{multiLanguage.alertField}</AvFeedback>
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="2">
                  <LabelRequired
                    fors="address"
                    label={`${multiLanguage.address} TUK`}
                  />
                </Col>
                <Col xs="12" md="3">
                  <PlacesAutocomplete
                    id="address"
                    name="address"
                    value={address}
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
                            placeholder:
                              multiLanguage.search +
                              " " +
                              multiLanguage.address +
                              "....",
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
                  <LabelRequired
                    fors="masa_berlaku"
                    label={multiLanguage.expiredDate}
                  />
                </Col>
                <Col xs="12" md="3">
                  <AvInput
                    type="date"
                    style={{}}
                    id="expired_date"
                    name="expired_date"
                    onChange={this.handleChange}
                  />
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col md="2">
                  <Label htmlFor="description">
                    {multiLanguage.description} TUK
                  </Label>
                </Col>
                <Col xs="12" md="8">
                  <AvInput
                    style={{}}
                    type="textarea"
                    id="description"
                    name="description"
                    onChange={this.handleChange}
                  />
                </Col>
              </AvGroup>
              <AvGroup row>
                <Col>
                  <Alert
                    color="danger"
                    hidden={this.state.hidden}
                    className="text-center"
                  >
                    {this.state.message}
                  </Alert>
                </Col>
              </AvGroup>
              <Row>
                <Col md="1.5">
                  <Link to={path_HomeTuk}>
                    <Button type="submit" size="md" color="danger">
                      <i className="fa fa-chevron-left" /> {multiLanguage.back}
                    </Button>
                  </Link>
                </Col>
                <Col md="1.5" className="Btn-Submit">
                  <Button
                    className="btn btn-success Btn-Submit"
                    color="success"
                    size="md"
                    type="submit"
                    onClick={this.handleSubmit}
                  >
                    <i className="fa fa-check" /> {multiLanguage.submit}
                  </Button>
                </Col>
              </Row>
            </AvForm>
          </CardBody>
        </Card>
      </div>
    );
  }
}

export default InputData_tuk;
