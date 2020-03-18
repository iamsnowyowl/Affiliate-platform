import React, { Component } from "react";
import { Link } from "react-router-dom";
import {
  Row,
  Col,
  Card,
  CardHeader,
  CardBody,
  Modal,
  ModalBody,
  ModalFooter,
  ModalHeader,
  Button
} from "reactstrap";
import Axios from "axios";

import {
  path_schema,
  baseUrl,
  updatePermission,
  deletePermission,
  getData,
  createPermission
} from "../../../components/config/config";
import { multiLanguage } from "../../../components/Language/getBahasa";
// import {SearchData} from '../../components/SearchTable/SearchData';

import "antd/dist/antd.css";
import "../../../css/loaderDataTable.css";
import ButtonDelete from "../../../components/Button/ButtonDelete";
import ButtonEdit from "../../../components/Button/ButtonEdit";
import TableList from "../../../components/ListTables/TableList";

class MainSchema extends Component {
  constructor(props) {
    super(props);
    this.state = {
      modal: false,
      payload: []
    };
  }

  toggle = row => {
    this.setState({
      modal: !this.state.modal
    });
    if (this.state.modal === false) {
      const path = path_schema + "/" + row.schema_id;
      Axios(getData(path, "GET"))
        .then(res => {
          if (res.data.responseStatus === "SUCCESS") {
            this.setState({
              payload: res.data.data
            });
          }
        })
        .catch(error => {
          console.log("error");
        });
    } else {
      console.log("tutup");
    }
  };

  render() {
    var item = "FACULTY";
    const columns = [
      {
        key: "schema_name",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.schemaName}
          </h5>
        ),
        dataIndex: "schema_name",
        sorter: true,
        width: "50%"
      },
      {
        key: "schema_id",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "schema_id",
        width: "50%",
        render: (value, row) => {
          if (
            updatePermission(item) === true &&
            deletePermission(item) === true
          ) {
            return (
              <div>
                <a
                  href={"/schema/main-schema/edit-mainSchema/" + value}
                  className="btn btn-success col-md-auto"
                  title={multiLanguage.Edit}
                >
                  <i className="fa fa-edit" />
                </a>{" "}
                <ButtonDelete id_delete={value} path={path_schema} />{" "}
                <Button color="primary" onClick={() => this.toggle(row)}>
                  detail
                </Button>
              </div>
            );
          } else if (
            updatePermission(item) === true &&
            deletePermission(item) === false
          ) {
            return (
              <div>
                <ButtonEdit
                  url={"/schema/main-schema/edit-mainSchema/" + value}
                  type="edit"
                />{" "}
                <Button color="primary" onClick={() => this.toggle(row)}>
                  detail
                </Button>
              </div>
            );
          } else if (
            updatePermission(item) === false &&
            deletePermission(item) === true
          ) {
            return (
              <div>
                <ButtonDelete id_delete={value} path={path_schema} />{" "}
                <Button color="primary" onClick={() => this.toggle(row)}>
                  detail
                </Button>
              </div>
            );
          } else {
            return (
              <div>
                <Button type="primary" onClick={() => this.toggle(row)}>
                  detail
                </Button>
              </div>
            );
          }
        }
      }
    ];
    return (
      <div className="animated fadeIn">
        <Modal
          isOpen={this.state.modal}
          toggle={this.toggle}
          className={this.props.className}
        >
          <ModalHeader toggle={this.toggle}>
            Detail {multiLanguage.schema} {this.state.payload.schema_name}
          </ModalHeader>
          <ModalBody>
            <Row>
              <Col>SKKNI/{multiLanguage.year}</Col>
              <Col>
                {this.state.payload.skkni}/{this.state.payload.skkni_year}
              </Col>
            </Row>
            <Row>
              <Col>{multiLanguage.schemaName}</Col>
              <Col>{this.state.payload.schema_name}</Col>
            </Row>
          </ModalBody>
          <ModalFooter>
            <Button color="danger" onClick={this.toggle}>
              cancel
            </Button>
          </ModalFooter>
        </Modal>

        <Card>
          <CardHeader>
            <Row>
              <Col md="6">
                <h5
                  style={{
                    textDecoration: "underline",
                    color: "navy"
                  }}
                >
                  {multiLanguage.listMainSchema}
                </h5>
              </Col>
              {createPermission(item) === true ? (
                <Col md="6" className="mb-3 mb-xl-0">
                  <Link to={"/schema/main-schema/add-mainCompetence"}>
                    <Button className="float-md-right" color="primary">
                      <i className="fa fa-plus" />{" "}
                      {` ${multiLanguage.add} ${multiLanguage.mainSchema}`}
                    </Button>
                  </Link>
                </Col>
              ) : (
                ""
              )}
            </Row>
          </CardHeader>
          <CardBody>
            <TableList
              columns={columns}
              urls={baseUrl + path_schema}
              path={path_schema}
              rowKeys={record => record.schema_id}
            />
          </CardBody>
        </Card>
      </div>
    );
  }
}

export default MainSchema;
