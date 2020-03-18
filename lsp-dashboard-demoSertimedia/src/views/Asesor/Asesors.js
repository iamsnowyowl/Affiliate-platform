import React, { Component } from "react";
import { Row, Col, Card, CardHeader, CardBody, Button } from "reactstrap";
import { Link } from "react-router-dom";

import {
  path_users,
  baseUrl,
  path_accessorsGeneral,
  listPermission
} from "../../components/config/config";
import { multiLanguage } from "../../components/Language/getBahasa";
import PagePermission from "../../components/PagePermission/PagePermission";
// import {SearchData} from '../../components/SearchTable/SearchData';

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import ButtonDelete from "../../components/Button/ButtonDelete";
import ButtonEdit from "../../components/Button/ButtonEdit";
import TableList from "../../components/ListTables/TableList";

class Assessors extends Component {
  render() {
    const columns = [
      {
        key: "picture",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.picture}
          </h5>
        ),
        dataIndex: "picture",
        render: value => {
          return (
            <img alt="asesi" src={baseUrl + value + "?width=56&height=56"} />
          );
        }
      },
      {
        key: "registration_number",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            No. REG Asesor
          </h5>
        ),
        dataIndex: "registration_number",
        sorter: true,
        width: "20%",
        render: (value, row) => {
          if (value === "undefined") {
            return <div style={{ textAlign: "left" }} />;
          } else {
            return <div style={{ textAlign: "left" }}>{value}</div>;
          }
        }
      },
      {
        key: "first_name",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.name}
          </h5>
        ),
        dataIndex: "first_name",
        render: (value, row) => {
          return row.first_name + " " + row.last_name;
        }
      },
      {
        key: "email",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>Email</h5>
        ),
        dataIndex: "email"
      },
      {
        key: "contact",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.contact}
          </h5>
        ),
        dataIndex: "contact"
      },
      {
        key: "user_id",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "user_id",
        width: "2%",
        render: value => {
          return (
            <div>
              <ButtonEdit
                url={path_users + "/" + value + "/Asesors"}
                type="edit"
              />
              <p style={{ marginBottom: "2%" }} />
              <a
                href={"/Assessors/list-skill/" + value}
                className="btn btn-warning"
                role="button"
                title={`${multiLanguage.list} Skill`}
              >
                <i className="fa fa-list-alt" />
              </a>
              <p style={{ marginBottom: "2%" }} />
              <ButtonDelete id_delete={value} path={path_users} />
            </div>
          );
        }
      }
    ];
    return listPermission("ACCESSOR") ? (
      <div className="animated fadeIn">
        <Card>
          <CardHeader>
            <Row>
              <Col>
                <h5
                  style={{
                    textDecoration: "underline",
                    color: "navy"
                  }}
                >
                  {multiLanguage.listAsesor}
                </h5>
              </Col>
              <Col>
                <Link to={"/Assessors/pending-competance"}>
                  <Button
                    className="float-md-right"
                    size="default"
                    style={{
                      backgroundColor: "#ffc107",
                      borderColor: "transparent"
                    }}
                  >
                    <i className="fa fa-exclamation" />{" "}
                    {multiLanguage.competencePending}
                  </Button>
                </Link>
              </Col>
              <Col md="1.5">
                <Link to={"/Assessors/schedule_accessors"}>
                  <Button
                    className="float-md-right"
                    size="default"
                    color="primary"
                  >
                    <i className="fa fa-calendar-o" />{" "}
                    {`${multiLanguage.scheduleAsesor}`}
                  </Button>
                </Link>
              </Col>
            </Row>
          </CardHeader>
          <CardBody>
            <TableList
              columns={columns}
              urls={baseUrl + path_users + path_accessorsGeneral}
              path={path_users + path_accessorsGeneral}
              rowKeys={record => record.user_id}
            />
          </CardBody>
        </Card>
      </div>
    ) : (
      <PagePermission />
    );
  }
}

export default Assessors;
