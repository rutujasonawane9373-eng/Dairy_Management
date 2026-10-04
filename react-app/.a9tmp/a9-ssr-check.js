import { renderToString } from "react-dom/server";
import { useState } from "react";
import { jsx, jsxs } from "react/jsx-runtime";
//#region src/App.jsx
var farmersData = [
	{
		farmerName: "Ramesh Patil",
		village: "Wadgaon",
		milkQuantity: 18.5,
		fatPercentage: 4.6,
		snf: 8.9,
		rate: 44,
		shift: "Morning",
		mobile: "9876543210",
		memberId: "DMS-101"
	},
	{
		farmerName: "Sunita Jadhav",
		village: "Wadgaon",
		milkQuantity: 12,
		fatPercentage: 3.8,
		snf: 8.2,
		rate: 40,
		shift: "Morning",
		mobile: "9823456710",
		memberId: "DMS-102"
	},
	{
		farmerName: "Vilas More",
		village: "Pimpri",
		milkQuantity: 22.5,
		fatPercentage: 4.9,
		snf: 9.1,
		rate: 45,
		shift: "Evening",
		mobile: "9765432198",
		memberId: "DMS-103"
	},
	{
		farmerName: "Anita Deshmukh",
		village: "Pimpri",
		milkQuantity: 15,
		fatPercentage: 4.2,
		snf: 8.6,
		rate: 42,
		shift: "Morning",
		mobile: "9654321987",
		memberId: "DMS-104"
	},
	{
		farmerName: "Ganesh Pawar",
		village: "Nagav",
		milkQuantity: 9.5,
		fatPercentage: 3.9,
		snf: 8.4,
		rate: 40,
		shift: "Evening",
		mobile: "9543219876",
		memberId: "DMS-105"
	},
	{
		farmerName: "Meena Kulkarni",
		village: "Nagav",
		milkQuantity: 20,
		fatPercentage: 4.7,
		snf: 9,
		rate: 44,
		shift: "Morning",
		mobile: "9432109876",
		memberId: "DMS-106"
	}
];
var siteTitle = "Dairy Management System";
var tagline = "Your daily partner for milk collection, fat & SNF tracking and farmer payments.";
var collectionDate = "21 September 2026";
var currentYear = 2026;
var contactInfo = "Dairy Cooperative Society Office, Main Road, Village | Phone: 98765 43210";
function formatLitres(litres) {
	return litres.toFixed(1);
}
function formatMoney(amount) {
	return Math.round(amount) + " Rs";
}
function Header({ siteTitle, tagline }) {
	return /* @__PURE__ */ jsxs("header", {
		style: {
			backgroundColor: "#e8b84b",
			textAlign: "center",
			padding: "25px 10px",
			borderBottom: "4px solid #c98a2d"
		},
		children: [/* @__PURE__ */ jsx("h1", {
			style: {
				margin: 0,
				color: "#4b2e0e",
				fontSize: "34px"
			},
			children: siteTitle
		}), /* @__PURE__ */ jsx("p", {
			style: {
				margin: "8px 0 0 0",
				color: "#5c3a12",
				fontSize: "16px"
			},
			children: tagline
		})]
	});
}
function StatCard({ title, description, isAlert }) {
	return /* @__PURE__ */ jsxs("article", {
		className: isAlert ? "stat-card stat-card-alert" : "stat-card",
		children: [/* @__PURE__ */ jsx("h3", {
			className: "stat-card-title",
			children: title
		}), /* @__PURE__ */ jsx("p", {
			className: "stat-card-text",
			children: description
		})]
	});
}
function Dashboard({ totalFarmers, litresToday, averageFat, paymentsPending, highMilkThreshold }) {
	return /* @__PURE__ */ jsxs("section", { children: [
		/* @__PURE__ */ jsx("h2", {
			className: "section-title",
			children: "Today's Overview"
		}),
		/* @__PURE__ */ jsxs("div", {
			className: "cards",
			children: [
				/* @__PURE__ */ jsx(StatCard, {
					title: "Total Farmers",
					description: totalFarmers + " registered milk producers in the village area."
				}),
				/* @__PURE__ */ jsx(StatCard, {
					title: "Milk Collected Today",
					description: formatLitres(litresToday) + " litres from " + totalFarmers + " farmers."
				}),
				/* @__PURE__ */ jsx(StatCard, {
					title: "Average Fat",
					description: averageFat.toFixed(1) + "% average fat content this week."
				}),
				paymentsPending > 0 ? /* @__PURE__ */ jsx(StatCard, {
					isAlert: true,
					title: "Payments Pending",
					description: formatMoney(paymentsPending) + " to be paid for the last fortnight."
				}) : /* @__PURE__ */ jsx(StatCard, {
					isAlert: true,
					title: "Payments Settled",
					description: "Every farmer of today's collection has been paid."
				})
			]
		}),
		litresToday >= highMilkThreshold ? /* @__PURE__ */ jsxs("p", {
			className: "badge badge-high",
			children: [
				"High Milk Collection - ",
				formatLitres(litresToday),
				" litres today, at or above the",
				" ",
				highMilkThreshold,
				" litre threshold."
			]
		}) : /* @__PURE__ */ jsxs("p", {
			className: "badge badge-normal",
			children: [
				"Normal Milk Collection - ",
				formatLitres(litresToday),
				" litres today, below the",
				" ",
				highMilkThreshold,
				" litre threshold."
			]
		})
	] });
}
function FarmerCard({ farmerName, village, milkQuantity, fatPercentage, snf, rate, shift, memberId, mobile, isSelected, showDetails, isPaid, onSelect, onTogglePayment }) {
	return /* @__PURE__ */ jsxs("article", {
		className: isSelected ? "farmer-card farmer-card-selected" : "farmer-card",
		children: [
			/* @__PURE__ */ jsxs("h3", {
				className: "card-title",
				children: ["Farmer: ", farmerName]
			}),
			/* @__PURE__ */ jsxs("p", {
				className: "card-text",
				children: [
					"Village: ",
					village,
					" | Milk: ",
					formatLitres(milkQuantity),
					" litres | Fat: ",
					fatPercentage,
					"%"
				]
			}),
			showDetails && /* @__PURE__ */ jsxs("div", {
				className: "farmer-details",
				children: [
					/* @__PURE__ */ jsxs("p", { children: [
						"Member ID: ",
						memberId,
						" | Mobile: ",
						mobile
					] }),
					/* @__PURE__ */ jsxs("p", { children: [
						"SNF: ",
						snf,
						"% | Shift: ",
						shift,
						" | Rate: ",
						rate,
						" Rs/litre"
					] }),
					/* @__PURE__ */ jsxs("p", { children: ["Estimated payment: ", formatMoney(milkQuantity * rate)] })
				]
			}),
			isPaid ? /* @__PURE__ */ jsx("span", {
				className: "badge badge-paid",
				children: "Payment Received"
			}) : /* @__PURE__ */ jsx("span", {
				className: "badge badge-pending",
				children: "Payment Pending"
			}),
			/* @__PURE__ */ jsxs("div", {
				className: "card-buttons",
				children: [/* @__PURE__ */ jsx("button", {
					className: "btn",
					onClick: () => onSelect(farmerName),
					children: isSelected ? "Selected Farmer" : "Select Farmer"
				}), /* @__PURE__ */ jsx("button", {
					className: "btn btn-secondary",
					onClick: () => onTogglePayment(farmerName),
					children: isPaid ? "Mark as Unpaid" : "Mark as Paid"
				})]
			})
		]
	});
}
function MilkCollectionCard({ farmerName, collectionDate, shift, snf, rate, milkQuantity, highMilkThreshold, onIncrease, onDecrease }) {
	return /* @__PURE__ */ jsxs("article", {
		className: "milk-card",
		children: [
			/* @__PURE__ */ jsx("h3", {
				className: "card-title",
				children: "Milk Collection Details"
			}),
			/* @__PURE__ */ jsxs("p", {
				className: "card-text",
				children: [
					"Farmer: ",
					farmerName,
					" | Date: ",
					collectionDate,
					" | Shift: ",
					shift,
					" | SNF: ",
					snf,
					"%"
				]
			}),
			/* @__PURE__ */ jsxs("p", {
				className: "card-text",
				children: [
					"Rate: ",
					rate,
					" Rs/litre | Estimated payment: ",
					formatMoney(milkQuantity * rate)
				]
			}),
			/* @__PURE__ */ jsxs("div", {
				className: "quantity-box",
				children: [
					/* @__PURE__ */ jsx("button", {
						className: "btn",
						onClick: onDecrease,
						disabled: milkQuantity <= 0,
						children: "- 0.5 L"
					}),
					/* @__PURE__ */ jsxs("span", {
						className: "quantity-value",
						children: [formatLitres(milkQuantity), " litres"]
					}),
					/* @__PURE__ */ jsx("button", {
						className: "btn",
						onClick: onIncrease,
						children: "+ 0.5 L"
					})
				]
			}),
			milkQuantity >= highMilkThreshold ? /* @__PURE__ */ jsx("p", {
				className: "badge badge-high",
				children: "High Milk Collection"
			}) : /* @__PURE__ */ jsx("p", {
				className: "badge badge-normal",
				children: "Normal Milk Collection"
			})
		]
	});
}
function Footer({ currentYear, contactInfo }) {
	const copyrightText = `© ${currentYear} Dairy Management System - College Web Development Project`;
	return /* @__PURE__ */ jsxs("footer", {
		style: {
			backgroundColor: "#4b2e0e",
			color: "#f5e6c8",
			textAlign: "center",
			padding: "18px 10px",
			marginTop: "20px"
		},
		children: [/* @__PURE__ */ jsx("p", {
			style: { margin: "4px 0" },
			children: contactInfo
		}), /* @__PURE__ */ jsx("p", {
			style: { margin: "4px 0" },
			children: copyrightText
		})]
	});
}
function App() {
	const [selectedFarmerName, setSelectedFarmerName] = useState(farmersData[0].farmerName);
	const [milkQuantity, setMilkQuantity] = useState(farmersData[0].milkQuantity);
	const [searchText, setSearchText] = useState("");
	const [showFarmerDetails, setShowFarmerDetails] = useState(false);
	const [paidFarmerNames, setPaidFarmerNames] = useState([]);
	const [highMilkThreshold, setHighMilkThreshold] = useState(20);
	function selectFarmer(name) {
		const farmer = farmersData.find((item) => item.farmerName === name);
		setSelectedFarmerName(name);
		setMilkQuantity(farmer.milkQuantity);
	}
	function increaseMilk() {
		setMilkQuantity((current) => Math.round((current + .5) * 10) / 10);
	}
	function decreaseMilk() {
		setMilkQuantity((current) => Math.max(0, Math.round((current - .5) * 10) / 10));
	}
	function togglePayment(name) {
		setPaidFarmerNames((current) => {
			if (current.includes(name)) return current.filter((item) => item !== name);
			return [...current, name];
		});
	}
	const selectedFarmer = farmersData.find((farmer) => farmer.farmerName === selectedFarmerName);
	const typedText = searchText.trim().toLowerCase();
	const visibleFarmers = farmersData.filter((farmer) => {
		return farmer.farmerName.toLowerCase().includes(typedText) || farmer.village.toLowerCase().includes(typedText);
	});
	const litresToday = farmersData.reduce((total, farmer) => {
		return total + (farmer.farmerName === selectedFarmerName ? milkQuantity : farmer.milkQuantity);
	}, 0);
	const averageFat = farmersData.reduce((total, farmer) => total + farmer.fatPercentage, 0) / farmersData.length;
	const paymentsPending = farmersData.filter((farmer) => !paidFarmerNames.includes(farmer.farmerName)).reduce((total, farmer) => {
		return total + (farmer.farmerName === selectedFarmerName ? milkQuantity : farmer.milkQuantity) * farmer.rate;
	}, 0);
	return /* @__PURE__ */ jsxs("div", {
		className: "App",
		children: [
			/* @__PURE__ */ jsx(Header, {
				siteTitle,
				tagline
			}),
			/* @__PURE__ */ jsxs("main", { children: [/* @__PURE__ */ jsx(Dashboard, {
				totalFarmers: farmersData.length,
				litresToday,
				averageFat,
				paymentsPending,
				highMilkThreshold
			}), /* @__PURE__ */ jsxs("section", { children: [
				/* @__PURE__ */ jsx("h2", {
					className: "section-title",
					children: "Latest Milk Collections"
				}),
				/* @__PURE__ */ jsxs("div", {
					className: "toolbar",
					children: [
						/* @__PURE__ */ jsx("label", {
							htmlFor: "farmer-search",
							children: "Search farmer"
						}),
						/* @__PURE__ */ jsx("input", {
							id: "farmer-search",
							type: "search",
							className: "field-input",
							placeholder: "Name or village",
							value: searchText,
							onChange: (event) => setSearchText(event.target.value)
						}),
						/* @__PURE__ */ jsx("label", {
							htmlFor: "threshold-select",
							children: "High collection from"
						}),
						/* @__PURE__ */ jsxs("select", {
							id: "threshold-select",
							className: "field-input",
							value: highMilkThreshold,
							onChange: (event) => setHighMilkThreshold(Number(event.target.value)),
							children: [
								/* @__PURE__ */ jsx("option", {
									value: "15",
									children: "15 litres"
								}),
								/* @__PURE__ */ jsx("option", {
									value: "20",
									children: "20 litres"
								}),
								/* @__PURE__ */ jsx("option", {
									value: "25",
									children: "25 litres"
								})
							]
						}),
						/* @__PURE__ */ jsx("button", {
							className: "btn btn-secondary",
							onClick: () => setShowFarmerDetails((current) => !current),
							children: showFarmerDetails ? "Hide Farmer Details" : "Show Farmer Details"
						}),
						/* @__PURE__ */ jsxs("span", {
							className: "toolbar-note",
							children: [
								visibleFarmers.length,
								" of ",
								farmersData.length,
								" farmers shown"
							]
						})
					]
				}),
				visibleFarmers.length === 0 ? /* @__PURE__ */ jsx("p", {
					className: "no-result",
					children: "No farmer found for \"" + searchText + "\". Clear the search box to see all farmers."
				}) : visibleFarmers.map((farmer) => /* @__PURE__ */ jsx(FarmerCard, {
					farmerName: farmer.farmerName,
					village: farmer.village,
					milkQuantity: farmer.farmerName === selectedFarmerName ? milkQuantity : farmer.milkQuantity,
					fatPercentage: farmer.fatPercentage,
					snf: farmer.snf,
					rate: farmer.rate,
					shift: farmer.shift,
					memberId: farmer.memberId,
					mobile: farmer.mobile,
					isSelected: farmer.farmerName === selectedFarmerName,
					showDetails: showFarmerDetails,
					isPaid: paidFarmerNames.includes(farmer.farmerName),
					onSelect: selectFarmer,
					onTogglePayment: togglePayment
				}, farmer.farmerName)),
				/* @__PURE__ */ jsx(MilkCollectionCard, {
					farmerName: selectedFarmerName,
					collectionDate,
					shift: selectedFarmer.shift,
					snf: selectedFarmer.snf,
					rate: selectedFarmer.rate,
					milkQuantity,
					highMilkThreshold,
					onIncrease: increaseMilk,
					onDecrease: decreaseMilk
				})
			] })] }),
			/* @__PURE__ */ jsx(Footer, {
				currentYear,
				contactInfo
			})
		]
	});
}
//#endregion
//#region src/a9-ssr-check.jsx
var html = renderToString(/* @__PURE__ */ jsx(App, {}));
console.log("RENDER_OK length=" + html.length);
console.log(html);
//#endregion
export {};
