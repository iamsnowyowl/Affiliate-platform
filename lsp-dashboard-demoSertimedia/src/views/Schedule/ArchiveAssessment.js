import React, { Component } from "react";
import { Card, CardHeader, CardBody } from "reactstrap";
import { multiLanguage } from "../../components/Language/getBahasa";
import TableList from "../../components/ListTables/TableList";
import {
  baseUrl,
  path_archive,
  formatDate
} from "../../components/config/config";

class ArchiveAssessment extends Component {
  handleDownload = row => {
    console.log("id archive", row);
  };

  render() {
    const columns = [
      {
        key: "title",
        title: (
          <h5 style={{ fontWeight: "bold" }}>{multiLanguage.assessmentName}</h5>
        ),
        dataIndex: "title",
        sorter: true
      },
      {
        key: "address",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.address}</h5>,
        dataIndex: "address",
        width: "25%"
      },
      {
        key: "tuk_name",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.tukName}</h5>,
        dataIndex: "tuk_name",
        sorter: true
      },
      {
        key: "end_date",
        title: (
          <h5 style={{ fontWeight: "bold" }}>
            {multiLanguage.endDate} {multiLanguage.Assessment}
          </h5>
        ),
        dataIndex: "end_date",
        render: value => {
          return formatDate(value);
        }
      },
      {
        key: "archive_id",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.action}</h5>,
        dataIndex: "archive_id",
        render: value => {
          return (
            <a
              href={baseUrl + path_archive + "/" + value + "/downloads"}
              target="_blank"
            >
              {multiLanguage.DownloadLink} {multiLanguage.archives}
            </a>
          );
        }
      }
    ];
    return (
      <div className="animated fadeIn">
        <Card>
          <CardHeader>
            <h5
              style={{
                textDecoration: "underline",
                color: "navy"
              }}
            >
              {multiLanguage.archives}{" "}
            </h5>
          </CardHeader>
          <CardBody>
            {/* table */}
            <TableList
              columns={columns}
              urls={baseUrl + path_archive}
              path={path_archive}
            />
          </CardBody>
        </Card>
      </div>
    );
  }
}

export default ArchiveAssessment;
