<?php

class NUA {

    public function FirstApplicationData($AddrLn1, $AddrLn2 , $AddrLn3 , $AppRef, $Consent, $Country, $County, $DOB, $EmailAddr, $FirstName, $Gender, $Mnumber, $PostalCod, $Prod, $SrcCode, $Surname) {

		return $AppRef;
	}

	public function UpdatedApplicationData($AppRef, $Consent, $EmailAddr, $EmpName, $EmpStat, $IntAmt, $MarStat, $MntRpyAmt, $Mnumber, $National, $NumCCs, $NumDep, $OLCCmr, $Occ, $OthHHInc, $Premise, $Purpose, $RentInc, $ReqLnAmt, $ReqTerm, $ResStat, $SalaryPayment, $SocWelfInc, $SrcCode, $Subpremise, $TimeInJob, $TmCurAdd, $TmInRoI, $TotMthLnRpy, $TotMthRntMrt, $TotNMthIn, $ValCCLims, $ValODLims, $ValSavsInv) {

		return "A";
	}

	public function FinishApplicationData($AppRef, $MchMMM, $MchPoB, $MrktPEmail, $MrktPPost, $MrktPTe, $SrcCod, $WorkTN) {

		return "OK";
	}

}
