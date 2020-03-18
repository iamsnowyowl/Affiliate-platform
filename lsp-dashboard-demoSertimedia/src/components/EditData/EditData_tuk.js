import React, { Component } from "react";
import { Redirect } from "react-router-dom";
import axios from "axios";
import {
  Button,
  Card,
  CardBody,
  CardFooter,
  CardHeader,
  Col,
  Form,
  FormGroup,
  Input,
  Label,
  Alert,
  Row
} from "reactstrap";
import { Digest } from "../../containers/Helpers/digest";
import { Link } from "react-router-dom";
import {
  baseUrl,
  path_tuk,
  path_tukAdd,
  Capital,
  getData,
  formatCapitalize
} from "../../components/config/config";
import PlacesAutocomplete, {
  geocodeByAddress,
  getLatLng,
  geocodeByPlaceId
} from "react-places-autocomplete";
import moment from "moment";
import { multiLanguage } from "../Language/getBahasa";

class EditData_tuk extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: {
        tuk_name: "",
        address: "",
        longitude: "",
        latitude: "",
        number_sk: "",
        contact: "",
        expired_date: ""
      },
      address: "",
      longitude: "",
      latitude: "",
      message: "",
      editTuk: false,
      hidden: true,
      payload: []
    };
  }

  componentDidMount() {
    const tuk_id = this.props.match.params.tuk_id;
    axios(getData(path_tuk + "/" + tuk_id, "GET")).then(request => {
      this.setState({
        payload: request.data.data,
        address: request.data.data.address,
        expired_date: request.data.data.expired_date
      });
    });
  }

  handleChange = event => {
    this.setState({ [event.target.name]: event.target.value });
  };

  handleChangePlace = address => {
    this.setState({
      address
    });
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

  handleClick = event => {
    event.preventDefault();
    const tuk_id = this.props.match.params.tuk_id;
    const data = {};

    if (this.state.tuk_name !== undefined)
      data["tuk_name"] = formatCapitalize(this.state.tuk_name);
    if (this.state.address !== "")
      data["address"] = formatCapitalize(this.state.address);
    if (this.state.longitude !== "") data["longitude"] = this.state.longitude;
    if (this.state.latitude !== "") data["latitude"] = this.state.latitude;
    if (this.state.number_sk !== undefined)
      data["number_sk"] = this.state.number_sk;
    if (this.state.tuk_type !== undefined)
      data["tuk_type"] = this.state.tuk_type;
    if (this.state.expired_date !== undefined)
      data["expired_date"] = this.state.expired_date;
    if (this.state.contact !== undefined) data["contact"] = this.state.contact;

    const authentication = Digest(path_tukAdd + "/" + tuk_id, "PUT");
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        "X-Lsp-Date": authentication.date,
        "Content-Type": "multipart/form-data"
      },
      url: baseUrl + path_tukAdd + "/" + tuk_id,
      data: data
    };
    axios(options)
      .then(response => {
        if (response.data.responseStatus === "SUCCESS") {
          this.setState({ editTuk: true });
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        if (responseJSON.data.responseStatus === "ERROR") {
          this.setState({ hidden: false, message: "Not change" });
        }
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
    const { expired_date, payload } = this.state;
    const {
      tuk_name,
      longitude,
      latitude,
      number_sk,
      contact,
      tuk_type
    } = payload;
    if (this.state.editTuk) {
      return <Redirect to={"/tuk"} />;
    }
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader style={{ textAlign: "center" }}>
            {multiLanguage.Edit} Data TUK
          </CardHeader>
          <CardBody>
            <Form
              action=""
              encType="multipart/form-data"
              className="form-horizintal"
            >
              <FormGroup row>
                <Col md="2">
                  <Label htmlFor="tuk_name">{multiLanguage.name}</Label>
                </Col>
                <Col xs="9" md="5">
                  <Input
                    type="text"
                    style={{ borderColor: "black" }}
                    id="tuk_name"
                    name="tuk_name"
                    onChange={this.handleChange}
                    defaultValue={tuk_name}
                  />
                </Col>
                <Col md="1">
                  <Label htmlFor="tuk_sk">No SK</Label>
                </Col>
                <Col xs="12" md="3">
                  <Input
                    type="text"
                    style={{ borderColor: "black" }}
                    id="number_sk"
                    name="number_sk"
                    onChange={this.handleChange}
                    defaultValue={number_sk === "undefined" ? "" : number_sk}
                    required
                  />
                </Col>
              </FormGroup>
              <FormGroup row>
                <Col md="2">
                  <Label htmlFor="tuk_name">{multiLanguage.address}</Label>
                </Col>
                <Col xs="9" md="5">
                  {Capital(this.state.address)},{" "}
                  <a
                    href={`https://www.google.com/maps/search/?api=1&query=${latitude},${longitude}`}
                    target="_blank"
                  >
                    {` ${multiLanguage.open} ${multiLanguage.map}`}
                  </a>
                </Col>
                <Col md="1">
                  <Label htmlFor="contact">{multiLanguage.contact}</Label>
                </Col>
                <Col xs="12" md="3">
                  <Input
                    type="text"
                    style={{ borderColor: "black" }}
                    id="contact"
                    name="contact"
                    onChange={this.handleChange}
                    defaultValue={contact === "undefined" ? "" : contact}
                  />
                </Col>
              </FormGroup>
              <FormGroup row>
                <Col md="2">
                  <Label htmlFor="address">{multiLanguage.newAddress}</Label>
                </Col>
                <Col xs="12" md="5">
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
                            placeholder: "Search Places ...",
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
                <Col md="1">
                  <Label htmlFor="contact">{multiLanguage.type} TUK</Label>
                </Col>
                <Col xs="12" md="3">
                  <Input
                    style={{ borderColor: "black" }}
                    type="select"
                    id="tuk_type"
                    name="tuk_type"
                    onChange={this.handleChange}
                    required
                  >
                    <option value="">{tuk_type}</option>
                    <option value="mandiri">TUK Mandiri</option>
                    <option value="sewaktu">TUK Sewaktu</option>
                    <option value="tempat_kerja">TUK Tempat Kerja</option>
                  </Input>
                </Col>
              </FormGroup>
              <FormGroup row>
                <Col md="2">
                  <Label htmlFor="masa_berlaku">
                    {multiLanguage.expiredDate}
                  </Label>
                </Col>
                <Col xs="12" md="5">
                  <Input
                    style={{ borderColor: "black" }}
                    type="date"
                    id="expired_date"
                    name="expired_date"
                    value={moment(expired_date).format("YYYY-MM-DD")}
                    onChange={this.handleChange}
                  />
                </Col>
              </FormGroup>
            </Form>
            <Alert
              color="danger"
              hidden={this.state.hidden}
              className="text-center"
            >
              {this.state.message}
            </Alert>
          </CardBody>
          <CardFooter>
            <Row>
              <Col md="1.5">
                <Link to={"/tuk"}>
                  <Button type="submit" size="md" color="danger">
                    <i className="fa fa-close" /> {multiLanguage.cancel}
                  </Button>
                  <p />
                </Link>
              </Col>
              <Col md="1.5">
                <Button
                  className="Btn-Submit"
                  type="submit"
                  size="md"
                  color="success"
                  onClick={this.handleClick}
                >
                  <i className="fa fa-check" /> {multiLanguage.submit}
                </Button>
              </Col>
            </Row>
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default EditData_tuk;
